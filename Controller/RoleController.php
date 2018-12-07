<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use RevisionTen\CMS\Command\RoleCreateCommand;
use RevisionTen\CMS\Command\RoleEditCommand;
use RevisionTen\CMS\Model\Role;
use RevisionTen\CMS\Model\RoleRead;
use RevisionTen\CMS\Model\UserRead;
use RevisionTen\CQRS\Services\AggregateFactory;
use RevisionTen\CQRS\Services\CommandBus;
use RevisionTen\CQRS\Services\MessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class RoleController.
 *
 * @Route("/admin")
 */
class RoleController extends AbstractController
{
    /**
     * @Route("/role/create", name="cms_role_create")
     *
     * @param Request                $request
     * @param CommandBus             $commandBus
     * @param MessageBus             $messageBus
     * @param TranslatorInterface    $translator
     *
     * @return Response|RedirectResponse|JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, CommandBus $commandBus, MessageBus $messageBus, TranslatorInterface $translator)
    {
        /** @var UserRead $user */
        $user = $this->getUser();

        $form = $this->roleForm([]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload = $form->getData();

            // Create the aggregate.
            $aggregateUuid = Uuid::uuid1()->toString();
            $success = false;
            $successCallback = function ($commandBus, $event) use (&$success) { $success = true; };
            $commandBus->dispatch(new RoleCreateCommand($user->getId(), null, $aggregateUuid, 0, [
                'title' => $payload['title'],
                'permissions' => $payload['permissions'],
            ], $successCallback), false);

            if (!$success) {
                return new JsonResponse($messageBus->getMessagesJson());
            }

            $this->addFlash(
                'success',
                $translator->trans('Role created')
            );

            return $this->redirectToRoles();
        }

        return $this->render('@cms/Form/form.html.twig', [
            'title' => 'Add role',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/role/edit/{id}", name="cms_role_edit")
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param CommandBus             $commandBus
     * @param MessageBus             $messageBus
     * @param AggregateFactory       $aggregateFactory
     * @param TranslatorInterface    $translator
     * @param int                    $id
     *
     * @return Response|RedirectResponse|JsonResponse
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, CommandBus $commandBus, MessageBus $messageBus, AggregateFactory $aggregateFactory, TranslatorInterface $translator, int $id)
    {
        /** @var UserRead $user */
        $user = $this->getUser();

        $roleRead = $entityManager->getRepository(RoleRead::class)->find($id);
        if (null === $roleRead) {
            return $this->redirectToRoles();
        }

        /** @var Role $roleAggregate */
        $roleAggregate = $aggregateFactory->build($roleRead->getUuid(), Role::class);

        $form = $this->roleForm([
            'title' => $roleAggregate->title,
            'permissions' => $roleAggregate->permissions,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Update the aggregate.
            $success = false;
            $successCallback = function ($commandBus, $event) use (&$success) { $success = true; };
            $commandBus->dispatch(new RoleEditCommand($user->getId(), null, $roleAggregate->getUuid(), $roleAggregate->getVersion(), [
                'title' => $data['title'],
                'permissions' => $data['permissions'],
            ], $successCallback), false);

            if (!$success) {
                return new JsonResponse($messageBus->getMessagesJson());
            }

            $this->addFlash(
                'success',
                $translator->trans('Role edited')
            );
        }

        return $this->render('@cms/Form/form.html.twig', [
            'title' => 'Edit role',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return RedirectResponse
     */
    private function redirectToRoles(): RedirectResponse
    {
        return $this->redirect('/admin/?entity=RoleRead&action=list');
    }

    private function roleForm(array $defaultData): FormInterface
    {
        $formBuilder = $this->createFormBuilder($defaultData);

        $formBuilder->add('title', TextType::class, [
            'label' => 'Title',
            'constraints' => new NotBlank(),
        ]);

        $config = $this->getParameter('cms');
        $permissionGroups = $config['permissions'];
        $size = 0;
        $permissionChoices = array_map(function ($permissions) use (&$size) {
            ++$size;
            $choices = array_map(function ($permission) use (&$size) {
                ++$size;
                return $permission['label'];
            }, $permissions);

            return array_flip($choices);
        }, $permissionGroups);

        $formBuilder->add('permissions', ChoiceType::class, [
            'label' => 'Permissions',
            'choices' => $permissionChoices,
            'multiple' => true,
            'expanded' => true,
            'required' => false,
            'attr' => [
                'size' => $size,
            ],
        ]);

        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'Save role',
            'attr' => [
                'class' => 'btn-primary',
            ],
        ]);

        return $formBuilder->getForm();
    }
}
