<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Controller;

use Doctrine\ORM\EntityManagerInterface;
use RevisionTen\CMS\Model\FileRead;
use RevisionTen\CMS\Services\FileService;
use RevisionTen\CQRS\Services\AggregateFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use RevisionTen\CMS\Model\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class FileController.
 */
class FileController extends AbstractController
{
    /**
     * @Route("/admin/file/picker", name="cms_file_picker")
     *
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Doctrine\ORM\EntityManagerInterface           $entityManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getFilePicker(RequestStack $requestStack, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('file_list');

        $request = $requestStack->getMasterRequest();
        if (null === $request) {
            throw new NotFoundHttpException();
        }

        $criteria = [
            'website' => $request->get('currentWebsite'),
        ];
        $mimeTypes = $request->get('mimeTypes');
        if ($mimeTypes) {
            $criteria['mimeType'] = explode(',', $mimeTypes);
        }

        $orderBy = [
            'created' => 'ASC',
        ];

        $files = $entityManager->getRepository(FileRead::class)->findBy($criteria, $orderBy);

        return $this->render('@cms/Admin/File/picker.html.twig', [
            'files' => array_reverse($files),
        ]);
    }

    /**
     * @Route("/file/{fileUuid}", name="cms_file_show")
     *
     * @param EntityManagerInterface $entityManager
     * @param string                 $fileUuid
     *
     * @return RedirectResponse
     */
    public function fileShow(EntityManagerInterface $entityManager, string $fileUuid): RedirectResponse
    {
        /** @var FileRead|null $fileRead */
        $fileRead = $entityManager->getRepository(FileRead::class)->findOneBy(['uuid' => $fileUuid]);

        if (null === $fileRead) {
            throw new NotFoundHttpException();
        }

        return $this->redirect($fileRead->getPath());
    }

    /**
     * @param AggregateFactory $aggregateFactory
     * @param RequestStack     $requestStack
     *
     * @return Response
     * @throws \Exception
     */
    public function files(AggregateFactory $aggregateFactory, RequestStack $requestStack): Response
    {
        $this->denyAccessUnlessGranted('file_list');

        /** @var File[] $files */
        $files = $aggregateFactory->findAggregates(File::class);
        // Sort by created date.
        usort($files, 'self::sortByCreated');
        $files = array_reverse($files);

        // Filter files by current website.
        $request = $requestStack->getMasterRequest();
        if ($request && $currentWebsite = $request->get('currentWebsite')) {
            $files = array_filter($files, static function ($file) use ($currentWebsite) {
                /** @var File $file */
                return $file->website === $currentWebsite;
            });
        }

        return $this->render('@cms/Admin/File/element-list.html.twig', [
            'files' => $files,
        ]);
    }

    private static function sortByCreated($a, $b)
    {
        $a = (string) $a->created->getTimestamp();
        $b = (string) $b->created->getTimestamp();

        return strcmp($a, $b);
    }

    /**
     * @Route("/admin/file/create", name="cms_file_create")
     *
     * @param Request     $request
     * @param FileService $fileService
     *
     * @return Response
     */
    public function fileCreate(Request $request, FileService $fileService): Response
    {
        $this->denyAccessUnlessGranted('file_create');

        $uploadDir = '/uploads/managed-files/';
        $currentWebsite = $request->get('currentWebsite');
        $config = $this->getParameter('cms');

        $builder = $this->createFormBuilder();

        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'constraints' => new NotBlank(),
            'attr' => [
                'placeholder' => 'Title',
            ],
        ]);

        $builder->add('language', ChoiceType::class, [
            'label' => 'Language',
            'choices' => $config['page_languages'] ?: [
                'English' => 'en',
                'German' => 'de',
            ],
            'placeholder' => 'Language',
            'constraints' => new NotBlank(),
            'attr' => [
                'class' => 'custom-select',
            ],
        ]);

        $builder->add('file', FileType::class, [
            'label' => 'Please select the file you want to upload.',
            'constraints' => new NotBlank(),
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Upload file',
            'attr' => [
                'class' => 'btn-primary',
            ],
        ]);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $data['file'] = $fileService->createFile(null, $data['file'], $data['title'], $uploadDir, $currentWebsite, $data['language']);

            return $this->render('@cms/Admin/File/create-success.html.twig', $data);
        }

        return $this->render('@cms/Form/file-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/file/edit", name="cms_file_edit")
     *
     * @param Request                $request
     * @param FileService            $fileService
     * @param AggregateFactory       $aggregateFactory
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function fileEdit(Request $request, FileService $fileService, AggregateFactory $aggregateFactory, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('file_edit');

        $fileRead = $entityManager->getRepository(FileRead::class)->find($request->get('id'));
        if (null === $fileRead) {
            return $this->redirect('/admin');
        }
        $fileUuid = $fileRead->getUuid();

        /** @var File $fileAggregate */
        $fileAggregate = $aggregateFactory->build($fileUuid, File::class);
        $uploadDir = '/uploads/managed-files/';
        $config = $this->getParameter('cms');

        $builder = $this->createFormBuilder([
            'title' => $fileAggregate->title,
            'language' => $fileAggregate->language,
        ]);

        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'constraints' => new NotBlank(),
            'attr' => [
                'placeholder' => 'Title',
            ],
        ]);

        $builder->add('language', ChoiceType::class, [
            'label' => 'Language',
            'choices' => $config['page_languages'] ?: [
                'English' => 'en',
                'German' => 'de',
            ],
            'placeholder' => 'Language',
            'constraints' => new NotBlank(),
            'attr' => [
                'class' => 'custom-select',
            ],
        ]);

        $builder->add('file', FileType::class, [
            'label' => 'replace file',
            'required' => false,
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Save file',
            'attr' => [
                'class' => 'btn-primary',
            ],
        ]);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $data['file'] = $fileService->replaceFile([
                'uuid' => $fileUuid,
            ], $data['file'], $data['title'], $uploadDir, $data['language']);

            return $this->render('@cms/Admin/File/create-success.html.twig', $data);
        }

        return $this->render('@cms/Form/file-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
