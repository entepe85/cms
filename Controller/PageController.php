<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use RevisionTen\CMS\Command\PageAddScheduleCommand;
use RevisionTen\CMS\Command\PageCloneCommand;
use RevisionTen\CMS\Command\PageDeleteCommand;
use RevisionTen\CMS\Command\PagePublishCommand;
use RevisionTen\CMS\Command\PageRemoveScheduleCommand;
use RevisionTen\CMS\Command\PageRollbackCommand;
use RevisionTen\CMS\Command\PageSaveOrderCommand;
use RevisionTen\CMS\Command\PageSubmitCommand;
use RevisionTen\CMS\Command\PageUnpublishCommand;
use RevisionTen\CMS\Form\PageType;
use RevisionTen\CMS\Model\Alias;
use RevisionTen\CMS\Model\Domain;
use RevisionTen\CMS\Model\Page;
use RevisionTen\CMS\Command\PageChangeSettingsCommand;
use RevisionTen\CMS\Command\PageCreateCommand;
use RevisionTen\CMS\Model\PageStreamRead;
use RevisionTen\CMS\Model\UserRead;
use RevisionTen\CMS\Model\Website;
use RevisionTen\CMS\Services\PageService;
use RevisionTen\CMS\Utilities\ArrayHelpers;
use RevisionTen\CQRS\Services\AggregateFactory;
use RevisionTen\CQRS\Services\CommandBus;
use RevisionTen\CQRS\Services\EventStore;
use RevisionTen\CQRS\Services\MessageBus;
use RevisionTen\CQRS\Services\SnapshotStore;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Cocur\Slugify\Slugify;
use function array_map;
use function count;
use function in_array;
use function json_decode;
use function json_encode;

/**
 * Class PageController.
 *
 * @Route("/admin")
 */
class PageController extends AbstractController
{
    /** @var MessageBus */
    private $messageBus;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    private function _getToolbarRefreshHeaders(): array
    {
        $headers = [];
        if ('dev' === $this->getParameter('kernel.environment')) {
            $headers['Symfony-Debug-Toolbar-Replace'] = 1;
        }

        return $headers;
    }

    /**
     * A wrapper function to execute a Command.
     * Returns true if the command succeeds.
     *
     * @param CommandBus $commandBus
     * @param string $commandClass
     * @param array $data
     * @param string $aggregateUuid
     * @param int $onVersion
     * @param bool $queue
     * @param string|NULL $commandUuid
     * @param int|NULL $userId
     *
     * @return bool
     * @throws Exception
     */
    private function _runCommand(CommandBus $commandBus, string $commandClass, array $data, string $aggregateUuid, int $onVersion, bool $queue = false, string $commandUuid = null, int $userId = null): bool
    {
        if (null === $userId) {
            /** @var UserRead $user */
            $user = $this->getUser();
            $userId = $user->getId();
        }

        $command = new $commandClass($userId, $commandUuid, $aggregateUuid, $onVersion, $data);

        return $commandBus->dispatch($command, $queue);
    }

    /**
     * Returns info from the messageBus.
     *
     * @return JsonResponse
     */
    private function _errorResponse(): JsonResponse
    {
        return new JsonResponse($this->messageBus->getMessagesJson());
    }

    /**
     * Redirects to the edit page of a Page Aggregate by its uuid.
     *
     * @param string $pageUuid
     *
     * @return RedirectResponse
     */
    private function _redirectToPage(string $pageUuid): RedirectResponse
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var PageStreamRead|null $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->findOneBy(['uuid' => $pageUuid]);

        if (!$pageStreamRead) {
            return $this->redirect('/admin');
        }

        return $this->redirectToRoute('cms_edit_aggregate', [
            'id' => $pageStreamRead->getId(),
        ]);
    }

    /**
     * Displays the Page Aggregate create form.
     *
     * @Route("/create-page", name="cms_create_page")
     *
     * @param Request                $request
     * @param CommandBus             $commandBus
     * @param TranslatorInterface    $translator
     *
     * @return Response|RedirectResponse
     *
     * @throws Exception
     */
    public function createPage(Request $request, CommandBus $commandBus, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('page_create');

        /** @var UserRead $user */
        $user = $this->getUser();
        $config = $this->getParameter('cms');
        $ignore_validation = $request->get('ignore_validation');
        $currentWebsite = (int) $request->get('currentWebsite');

        $data = [];
        $data['language'] = $request->getLocale();
        $pageWebsites = [];
        /** @var Website[] $websites */
        $websites = $websites = $user->getWebsites();
        foreach ($websites as $website) {
            $pageWebsites[$website->getTitle()] = $website->getId();
            if ($website->getId() === $currentWebsite && $website->getDefaultLanguage()) {
                $data['language'] = $website->getDefaultLanguage();
            }
        }

        if (!empty($request->get('page'))) {
            $data = null;
        }

        $pageType = $config['page_type'] ?? PageType::class;

        $form = $this->createForm($pageType, $data, [
            'page_websites' => $currentWebsite ? false : $pageWebsites,
            'page_templates' => $config['page_templates'] ?? null,
            'page_languages' => $config['page_languages'] ?? null,
            'page_metatype' => $config['page_metatype'] ?? null,
            'validation_groups' => $ignore_validation ? false : null,
            'allow_extra_fields' => true,
        ]);

        $form->handleRequest($request);

        if (!$ignore_validation && $form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($currentWebsite) {
                $data['website'] = $currentWebsite;
            }

            $pageUuid = Uuid::uuid1()->toString();
            $success = $this->_runCommand($commandBus, PageCreateCommand::class, $data, $pageUuid, 0);

            return $success ? $this->_redirectToPage($pageUuid) : $this->_errorResponse();
        }

        return $this->render('@cms/Form/form.html.twig', [
            'title' => $translator->trans('Add page'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays the page settings form.
     *
     * @Route("/change-page-settings/{pageUuid}/{version}/", name="cms_change_pagesettings")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param AggregateFactory $aggregateFactory
     * @param TranslatorInterface $translator
     * @param EntityManagerInterface $entityManager
     * @param string $pageUuid
     * @param int $version
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function changePageSettings(Request $request, CommandBus $commandBus, AggregateFactory $aggregateFactory, TranslatorInterface $translator, EntityManagerInterface $entityManager, string $pageUuid, int $version)
    {
        $this->denyAccessUnlessGranted('page_edit');

        /** @var UserRead $user */
        $user = $this->getUser();
        $config = $this->getParameter('cms');
        $ignore_validation = $request->get('ignore_validation');
        $currentWebsite = $request->get('currentWebsite');

        // Convert Aggregate to data array for form and remove properties we don't want changed.
        $aggregate = $aggregateFactory->build($pageUuid, Page::class, $version, $user->getId());
        $aggregateData = json_decode(json_encode($aggregate), true);
        unset($aggregateData['uuid'], $aggregateData['elements']);
        $aggregateWebsite = $aggregateData['website'] ?? $currentWebsite;

        /** @var Website[] $websites */
        $websites = $user->getWebsites();
        $pageWebsites = [];
        foreach ($websites as $website) {
            $pageWebsites[$website->getTitle()] = $website->getId();
        }
        if ($currentWebsite && $aggregateWebsite !== $currentWebsite && !in_array($currentWebsite, $pageWebsites, false)) {
            throw new AccessDeniedHttpException('Page does not exist on this website');
        }

        $pageType = $config['page_type'] ?? PageType::class;

        $form = $this->createForm($pageType, $aggregateData, [
            'page_websites' => $currentWebsite && count($pageWebsites) === 1 ? false : $pageWebsites,
            'page_templates' => $config['page_templates'] ?? null,
            'page_languages' => $config['page_languages'] ?? null,
            'page_metatype' => $config['page_metatype'] ?? null,
            'validation_groups' => $ignore_validation ? false : null,
            'allow_extra_fields' => true,
        ]);

        $form->handleRequest($request);

        // Get differences in data and check if data has changed.
        if (!$ignore_validation && $form->isSubmitted()) {
            $data = $form->getData();

            // Remove data that hasn't changed.
            $data = ArrayHelpers::diff($aggregateData, $data);

            if (empty($data)) {
                $form->addError(new FormError($translator->trans('Data has not changed.')));
            }

            if ($form->isValid()) {
                $success = $this->_runCommand($commandBus, PageChangeSettingsCommand::class, $data, $pageUuid, $version, true);

                if ($request->get('ajax')) {
                    return new JsonResponse([
                        'success' => $success,
                        'refresh' => null, // Refreshes whole page.
                    ]);
                }

                return $success ? $this->_redirectToPage($pageUuid) : $this->_errorResponse();
            }
        }

        return $this->render('@cms/Form/form.html.twig', [
            'title' => 'Change Page Settings',
            'form' => $form->createView(),
        ]);
    }

    /**
     * Submit queued events for a specific Page Aggregate and user.
     *
     * @Route("/submit-changes/{pageUuid}/{version}/{qeueUser}", name="cms_submit_changes")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param string $pageUuid
     * @param int $version
     * @param int $qeueUser
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function submitChanges(Request $request, CommandBus $commandBus, string $pageUuid, int $version, int $qeueUser)
    {
        $this->denyAccessUnlessGranted('page_submit_changes');

        /** @var UserRead $user */
        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('message', TextareaType::class, [
                'label' => 'Commit Message',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Describe the changes you made',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit changes',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $success = $this->_runCommand($commandBus, PageSubmitCommand::class, [
                'grantedBy' => $user->getId(),
                'message' => $data['message'],
            ], $pageUuid, $version, true, null, $qeueUser);

            if ($request->get('ajax')) {
                return new JsonResponse([
                    'success' => $success,
                ], 200, $this->_getToolbarRefreshHeaders());
            }

            return $success ? $this->_redirectToPage($pageUuid) : $this->_errorResponse();
        }

        return $this->render('@cms/Form/form.html.twig', [
            'title' => 'Submit changes',
            'form' => $form->createView(),
        ]);
    }

    /**
     * Remove a page schedule.
     *
     * @Route("/remove-schedule/{pageUuid}/{scheduleUuid}/{version}", name="cms_remove_schedule")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param string $pageUuid
     * @param string $scheduleUuid
     * @param int $version
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function removeSchedule(Request $request, CommandBus $commandBus, string $pageUuid, string $scheduleUuid, int $version)
    {
        $this->denyAccessUnlessGranted('page_schedule');

        /** @var UserRead $user */
        $user = $this->getUser();

        $success = $this->_runCommand($commandBus, PageRemoveScheduleCommand::class, [
            'scheduleUuid' => $scheduleUuid,
        ], $pageUuid, $version, false, null, $user->getId());

        if ($request->get('ajax')) {
            return new JsonResponse([
                'success' => $success,
            ], 200, $this->_getToolbarRefreshHeaders());
        }

        return $success ? $this->_redirectToPage($pageUuid) : $this->_errorResponse();
    }

    /**
     * Schedule a page.
     *
     * @Route("/schedule/{pageUuid}/{version}", name="cms_schedule_page")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param EntityManagerInterface $entityManager
     * @param string $pageUuid
     * @param int $version
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function schedule(Request $request, CommandBus $commandBus, EntityManagerInterface $entityManager, string $pageUuid, int $version)
    {
        $this->denyAccessUnlessGranted('page_schedule');

        /** @var PageStreamRead|null $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->findOneBy(['uuid' => $pageUuid]);

        if (null === $pageStreamRead) {
            return $this->_errorResponse();
        }

        /** @var UserRead $user */
        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add('startDate', DateTimeType::class, [
                'label' => 'Start date',
                'input' => 'timestamp',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'End date',
                'input' => 'timestamp',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Schedule',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $success = $this->_runCommand($commandBus, PageAddScheduleCommand::class, [
                'startDate' => $data['startDate'],
                'endDate' => $data['endDate'],
            ], $pageUuid, $version, false, null, $user->getId());

            $aliases = $pageStreamRead->getAliases();
            $hasAlias = !(null === $aliases || empty($aliases) || 0 === count($aliases));

            return $this->createAliasResponse($pageUuid, $request, $success, $hasAlias);
        }

        return $this->render('@cms/Form/form.html.twig', [
            'title' => 'Schedule',
            'form' => $form->createView(),
        ]);
    }

    /**
     * Inspect a page.
     *
     * @Route("/inspect/{pageUuid}", name="cms_inspect_page")
     *
     * @param Request          $request
     * @param AggregateFactory $aggregateFactory
     * @param string           $pageUuid
     *
     * @return JsonResponse|Response
     */
    public function inspect(Request $request, AggregateFactory $aggregateFactory, string $pageUuid)
    {
        $this->denyAccessUnlessGranted('page_inspect');

        /** @var UserRead $user */
        $user = $this->getUser();

        $page = $aggregateFactory->build($pageUuid, Page::class, null, $user->getId());

        return $this->render('@cms/Admin/Page/inspect.html.twig', [
            'title' => 'Inspect page',
            'page' => $page,
        ]);
    }

    /**
     * Delete all queued events for a specific Page Aggregate and user.
     *
     * @Route("/discard-changes/{pageUuid}", name="cms_discard_changes")
     *
     * @param EventStore $eventStore
     * @param string     $pageUuid
     *
     * @return Response
     */
    public function discardChanges(EventStore $eventStore, string $pageUuid): Response
    {
        $this->denyAccessUnlessGranted('page_edit');

        /** @var UserRead $user */
        $user = $this->getUser();

        $eventStore->discardQueued($pageUuid, $user->getId());

        return $this->_redirectToPage($pageUuid);
    }

    /**
     * Delete the last event from the event qeue
     * for a specific Page Aggregate and user.
     *
     * @Route("/undo-change/{pageUuid}/{version}", name="cms_undo_change")
     *
     * @param EventStore $eventStore
     * @param string     $pageUuid
     * @param int        $version
     *
     * @return Response
     */
    public function undoChange(EventStore $eventStore, string $pageUuid, int $version): Response
    {
        $this->denyAccessUnlessGranted('page_edit');

        /** @var UserRead $user */
        $user = $this->getUser();

        $eventStore->discardLatestQueued($pageUuid, $user->getId(), $version);

        return $this->_redirectToPage($pageUuid);
    }

    /**
     * Create an alias for a specific page.
     *
     * @Route("/create-alias/{pageUuid}", name="cms_create_alias")
     *
     * @param Request                $request
     * @param string                 $pageUuid
     * @param EntityManagerInterface $entityManager
     *
     * @return JsonResponse|Response
     */
    public function createAlias(Request $request, string $pageUuid, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('alias_create');

        $config = $this->getParameter('cms');

        /** @var PageStreamRead|null $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->findOneBy(['uuid' => $pageUuid]);

        if (null === $pageStreamRead) {
            return $this->_errorResponse();
        }

        /** @var Website $website */
        $website = $entityManager->getRepository(Website::class)->find($pageStreamRead->getWebsite());
        if (null === $website) {
            return $this->_errorResponse();
        }

        if ($website->getDomains()) {
            /** @var Domain $domain */
            $domain = $website->getDomains()->first();
            $websiteUrl = $request->getScheme().'://'.$domain->getDomain();
        } else {
            $websiteUrl = null;
        }
        $slugify = new Slugify();
        $alias_prefix = $config['page_templates'][$pageStreamRead->getTemplate()]['alias_prefix'][$pageStreamRead->getLanguage()] ?? '/';
        $pathSuggestion = $alias_prefix.$slugify->slugify($pageStreamRead->getTitle());

        $form = $this->createFormBuilder(['path' => $pathSuggestion])
            ->add('path', TextType::class, [
                'label' => 'Path',
                'required' => true,
                'attr' => [
                    'placeholder' => $pathSuggestion,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create alias',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $path = $data['path'];

            // Create new alias.
            $alias = new Alias();
            $alias->setPath($path);
            $alias->setLanguage($pageStreamRead->getLanguage());
            $alias->setWebsite($website);
            $alias->setPageStreamRead($pageStreamRead);

            // Persist alias.
            $entityManager->persist($alias);
            $entityManager->flush();
            $entityManager->clear();

            return $request->get('ajax') ? new JsonResponse([
                'success' => true,
            ]) : $this->_redirectToPage($pageUuid);
        }

        return $this->render('@cms/Form/alias-form.html.twig', [
            'title' => 'Create alias',
            'form' => $form->createView(),
            'websiteUrl' => $websiteUrl,
        ]);
    }

    /**
     * Redirect to alias create form If the page has no aliases.
     *
     * @param string  $pageUuid
     * @param Request $request
     * @param bool    $success
     * @param bool    $hasAlias
     *
     * @return JsonResponse|RedirectResponse
     */
    public function createAliasResponse(string $pageUuid, Request $request, bool $success, bool $hasAlias)
    {
        // Check if aliases exist for this page.
        if (!$hasAlias && $this->isGranted('alias_create')) {
            // The page has no aliases, show modal or page with alias create form.
            $url = $this->generateUrl('cms_create_alias', [
                'pageUuid' => $pageUuid,
            ]);
            return $request->get('ajax') ? new JsonResponse([
                'success' => $success,
                'modal' => $url,
            ], 200, $this->_getToolbarRefreshHeaders()) : $this->redirect($url);
        }

        if ($request->get('ajax')) {
            return new JsonResponse([
                'success' => $success,
            ], 200, $this->_getToolbarRefreshHeaders());
        }

        return $success ? $this->_redirectToPage($pageUuid) : $this->_errorResponse();
    }

    /**
     * Publishes a Page Aggregate.
     *
     * @Route("/publish-page/{pageUuid}/{version}", name="cms_publish_page")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param AggregateFactory $aggregateFactory
     * @param string $pageUuid
     * @param int $version
     * @param EntityManagerInterface $entityManager
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function publishPage(Request $request, CommandBus $commandBus, AggregateFactory $aggregateFactory, string $pageUuid, int $version, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('page_publish');

        /** @var PageStreamRead|null $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->findOneBy(['uuid' => $pageUuid]);

        if (null === $pageStreamRead) {
            return $this->_errorResponse();
        }

        /**
         * Get latest Page version first.
         *
         * @var Page $page
         */
        $page = $aggregateFactory->build($pageUuid, Page::class);
        $onVersion = $page->getVersion();

        $success = $this->_runCommand($commandBus, PagePublishCommand::class, [
            'version' => $version, // Todo: Not needed anymore, see listener.
        ], $pageUuid, $onVersion);

        if (!$success) {
            return $this->_errorResponse();
        }

        $aliases = $pageStreamRead->getAliases();
        $hasAlias = !(null === $aliases || empty($aliases) || 0 === count($aliases));

        return $this->createAliasResponse($pageUuid, $request, $success, $hasAlias);
    }

    /**
     * Unpublishes a Page Aggregate.
     *
     * @Route("/unpublish-page/{pageUuid}", name="cms_unpublish_page")
     *
     * @param CommandBus $commandBus
     * @param AggregateFactory $aggregateFactory
     * @param string $pageUuid
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function unpublishPage(CommandBus $commandBus, AggregateFactory $aggregateFactory, string $pageUuid)
    {
        $this->denyAccessUnlessGranted('page_unpublish');

        /**
         * Get latest Page version first.
         *
         * @var Page $page
         */
        $page = $aggregateFactory->build($pageUuid, Page::class);

        $success = $this->_runCommand($commandBus, PageUnpublishCommand::class, [], $pageUuid, $page->getVersion());

        if (!$success) {
            return $this->_errorResponse();
        }

        return $this->_redirectToPage($pageUuid);
    }

    /**
     * Saves a Page Aggregate Snapshot.
     *
     * @Route("/save-snapshot/{pageUuid}", name="cms_save_snapshot")
     *
     * @param AggregateFactory $aggregateFactory
     * @param SnapshotStore    $snapshotStore
     * @param string           $pageUuid
     *
     * @return Response
     */
    public function saveSnapshot(AggregateFactory $aggregateFactory, SnapshotStore $snapshotStore, string $pageUuid): Response
    {
        /**
         * Get latest Page version first.
         *
         * @var Page $page
         */
        $page = $aggregateFactory->build($pageUuid, Page::class);

        // Save Snapshot.
        $snapshotStore->save($page);

        return $this->_redirectToPage($pageUuid);
    }

    /**
     * Display the frontend page in edit mode.
     *
     * @Route("/edit/{pageUuid}/{user}", name="cms_page_edit")
     *
     * @param Request                $request
     * @param PageService            $pageService
     * @param EntityManagerInterface $entityManager
     * @param AggregateFactory       $aggregateFactory
     * @param EventStore             $eventStore
     * @param TranslatorInterface    $translator
     * @param string                 $pageUuid
     * @param int                    $user             the user that edits the page
     *
     * @return Response
     */
    public function pageEdit(Request $request, PageService $pageService, EntityManagerInterface $entityManager, AggregateFactory $aggregateFactory, EventStore $eventStore, TranslatorInterface $translator, string $pageUuid, int $user): Response
    {
        $this->denyAccessUnlessGranted('page_edit');

        $config = $this->getParameter('cms');

        /** @var UserRead|null $user */
        $user = $entityManager->getRepository(UserRead::class)->find($user);

        /** @var UserRead $realUser */
        $realUser = $this->getUser();

        if ($user->getId() === $realUser->getId()) {
            $edit = true;
        } else {
            $edit = false;
        }

        /** @var Page $page */
        $page = $aggregateFactory->build($pageUuid, Page::class, null, $user->getId());

        // Check if user has access to the aggregates current website.
        /** @var ArrayCollection $websites */
        $websites = $user->getWebsites();
        $websites = array_map(static function ($website) {
            /** @var Website $website */
            return $website->getId();
        }, $websites->toArray());
        $currentWebsite = $request->get('currentWebsite');
        if ($currentWebsite && $page->website !== $currentWebsite && !in_array($currentWebsite, $websites, false)) {
            throw new AccessDeniedHttpException('Page does not exist on this website');
        }

        // Get the first alias for this page.
        /** @var PageStreamRead $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->findOneBy(['uuid' => $pageUuid]);
        $alias = (null !== $pageStreamRead->getAliases()) ? $pageStreamRead->getAliases()->first() : null;

        // Get all queued Events for this page.
        /** @var UserRead[] $adminUsers */
        $adminUsers = $entityManager->getRepository(UserRead::class)->findAll();
        $users = [];
        foreach ($adminUsers as $key => $adminUser) {
            $eventStreamObjects = $eventStore->findQueued($pageUuid, $adminUser->getId(), null, $page->getStreamVersion() + 1);
            if ($eventStreamObjects) {
                $users[$adminUser->getId()] = [
                    'events' => $eventStreamObjects,
                    'user' => $adminUser,
                ];
            }
        }

        // Get the page template from the template name.
        $template = $config['page_templates'][$page->template]['template'] ?? '@cms/layout.html.twig';

        // Build element info for admin-frontend.js.
        $pageElements = [];
        foreach ($config['page_elements'] as $name => $element) {
            $pageElements[$name] = [
                'icon' => $element['icon'],
                'label' => $translator->trans($name),
                'name' => $name,
                'public' => $element['public'] ?? false,
            ];
        }

        $translations = [
            'addElement' => 'Add Element',
            'delete' => 'Delete',
            'edit' => 'Edit',
            'duplicate' => 'Duplicate',
            'shift' => 'Shift',
            'enable' => 'Enable',
            'disable' => 'Disable',
        ];

        $translations = array_map(static function ($value) use ($translator) {
            return $translator->trans($value);
        }, $translations);

        // Convert the page aggregate to a json payload.
        $pageData = json_decode(json_encode($page), true);
        // Hydrate the page with doctrine entities.
        $pageData = $pageService->hydratePage($pageData);

        // Get the pages website.
        $website = isset($pageData['website']) ? $entityManager->getRepository(Website::class)->find($pageData['website']) : null;

        return $this->render($template, [
            'website' => $website,
            'alias' => $alias,
            'page' => $pageData,
            'edit' => $edit,
            'user' => $user,
            'users' => $users,
            'config' => $config,
            'pageElements' => $pageElements,
            'translations' => $translations,
        ]);
    }

    /**
     * Display a frontend page.
     *
     * @Route("/page/preview/{pageUuid}", name="cms_page_preview")
     *
     * @param PageService            $pageService
     * @param AggregateFactory       $aggregateFactory
     * @param EntityManagerInterface $entityManager
     * @param string                 $pageUuid
     *
     * @return Response
     */
    public function page(PageService $pageService, AggregateFactory $aggregateFactory, EntityManagerInterface $entityManager, string $pageUuid): Response
    {
        $this->denyAccessUnlessGranted('page_edit');

        $config = $this->getParameter('cms');

        /** @var UserRead $user */
        $user = $this->getUser();

        /** @var PageStreamRead $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->findOneBy(['uuid' => $pageUuid]);
        $alias = (null !== $pageStreamRead->getAliases()) ? $pageStreamRead->getAliases()->first() : null;

        /** @var Page $page */
        $page = $aggregateFactory->build($pageUuid, Page::class, null, $user->getId());
        // Convert the page aggregate to a json payload.
        $pageData = json_decode(json_encode($page), true);
        // Filter disabled elements.
        $pageData = $pageService->filterPayload($pageData);
        // Hydrate the page with doctrine entities.
        $pageData = $pageService->hydratePage($pageData);

        // Get the page template from the template name.
        $templateName = $pageData['template'];
        $template = $config['page_templates'][$templateName]['template'] ?? '@cms/layout.html.twig';

        return $this->render($template, [
            'alias' => $alias,
            'page' => $pageData,
            'edit' => false,
            'config' => $config,
        ]);
    }

    /**
     * Clones a page.
     * Must ignore queued events on page because they might not exist in the future.
     *
     * @Route("/clone-aggregate", name="cms_clone_aggregate")
     *
     * @param Request                $request
     * @param CommandBus             $commandBus
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface    $translator
     *
     * @return Response
     *
     * @throws Exception
     */
    public function cloneAggregateAction(Request $request, CommandBus $commandBus, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('page_clone');

        /** @var int $id PageStreamRead Id. */
        $id = $request->get('id');

        /** @var PageStreamRead $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->find($id);

        if (null === $pageStreamRead) {
            return $this->redirect('/admin');
        }

        $data = [
            'originalUuid' => $pageStreamRead->getUuid(),
            'originalVersion' => $pageStreamRead->getVersion(),
        ];
        $pageUuid = Uuid::uuid1()->toString();

        $success = $this->_runCommand($commandBus, PageCloneCommand::class, $data, $pageUuid, 0);

        if (!$success) {
            return $this->_errorResponse();
        }

        $this->addFlash(
            'success',
            $translator->trans('Page Cloned')
        );

        return $this->_redirectToPage($pageUuid);
    }

    /**
     * @Route("/delete-aggregate", name="cms_delete_aggregate")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param EntityManagerInterface $entityManager
     * @param EventStore $eventStore
     * @param TranslatorInterface $translator
     *
     * @return Response
     * @throws Exception
     */
    public function deleteAggregateAction(Request $request, CommandBus $commandBus, EntityManagerInterface $entityManager, EventStore $eventStore, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('page_delete');

        /** @var UserRead $user */
        $user = $this->getUser();

        /** @var int $id FormRead Id. */
        $id = $request->get('id');

        /** @var PageStreamRead $pageStreamRead */
        $pageStreamRead = $entityManager->getRepository(PageStreamRead::class)->find($id);

        if (null === $pageStreamRead) {
            return $this->redirect('/admin');
        }

        $pageUuid = $pageStreamRead->getUuid();
        $version = $pageStreamRead->getVersion();

        // Discard this users queued changes before attempting to delete the aggregate.
        $eventStore->discardQueued($pageUuid, $user->getId());

        $success = $this->_runCommand($commandBus, PageDeleteCommand::class, [], $pageUuid, $version);

        if (!$success) {
            return $this->_errorResponse();
        }

        $this->addFlash(
            'success',
            $translator->trans('Page Deleted')
        );

        return $this->redirect('/admin/?entity=PageStreamRead&action=list');
    }

    /**
     * @Route("/rollback-aggregate/{pageUuid}/{version}", name="cms_rollback_aggregate")
     *
     * @param Request $request
     * @param CommandBus $commandBus
     * @param AggregateFactory $aggregateFactory
     * @param TranslatorInterface $translator
     * @param string $pageUuid
     * @param int $version
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function rollbackAggregateAction(Request $request, CommandBus $commandBus, AggregateFactory $aggregateFactory, TranslatorInterface $translator, string $pageUuid, int $version)
    {
        $this->denyAccessUnlessGranted('page_edit');

        /** @var UserRead $user */
        $user = $this->getUser();

        /** @var Page $pageAggregate */
        $pageAggregate = $aggregateFactory->build($pageUuid, Page::class, $version, $user->getId());

        $versionChoices = [];
        foreach ($pageAggregate->getHistory() as $event) {
            $label = $event['payload']['message'] ?? $translator->trans($event['message']);
            $versionChoices['Version '.$event['version'].' - '.$label] = $event['version'];
        }

        $form = $this->createFormBuilder()
            ->add('previousVersion', ChoiceType::class, [
                'label' => 'Previous Version',
                'required' => true,
                'choices' => $versionChoices,
                'attr' => [
                    'class' => 'custom-select',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rollback',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $success = $this->_runCommand($commandBus, PageRollbackCommand::class, [
                'previousVersion' => $data['previousVersion'],
            ], $pageUuid, $version, true);

            if ($success) {
                $this->addFlash(
                    'success',
                    $translator->trans('Page rolled back')
                );
            }

            if ($request->get('ajax')) {
                return new JsonResponse([
                    'success' => $success,
                    'refresh' => null, // Refreshes whole page.
                ]);
            }

            return $success ? $this->_redirectToPage($pageUuid) : $this->_errorResponse();
        }

        return $this->render('@cms/Form/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/page/save-order/{pageUuid}/{onVersion}", name="cms_page_saveorder")
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param CommandBus $commandBus
     * @param string $pageUuid
     * @param int $onVersion
     *
     * @return JsonResponse|RedirectResponse
     * @throws Exception
     */
    public function saveOrder(Request $request, TranslatorInterface $translator, CommandBus $commandBus, string $pageUuid, int $onVersion)
    {
        $this->denyAccessUnlessGranted('page_edit');

        $order = json_decode($request->getContent(), true);

        if ($order && isset($order[0])) {
            $order = $order[0];
            $order = ArrayHelpers::cleanOrderTree($order);
        }

        $success = $this->_runCommand($commandBus, PageSaveOrderCommand::class, [
            'order' => $order,
        ], $pageUuid, $onVersion, true);

        if (!$success) {
            return $this->_errorResponse();
        }

        $this->addFlash(
            'success',
            $translator->trans('Page element order saved')
        );

        if ($request->get('ajax')) {
            return new JsonResponse([
                'success' => $success,
                'refresh' => null, // refreshes whole page.
            ]);
        }

        return $this->_redirectToPage($pageUuid);
    }
}
