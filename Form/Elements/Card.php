<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Form\Elements;

use RevisionTen\CMS\Form\Types\DoctrineType;
use RevisionTen\CMS\Form\Types\UploadType;
use RevisionTen\CMS\Model\Alias;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class Card extends Element
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('styles', ChoiceType::class, [
                'label' => 'Choose how this card is displayed.',
                'choices' => $options['elementConfig']['styles'],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('imagePosition', ChoiceType::class, [
                'label' => 'Image position',
                'constraints' => new NotBlank(),
                'choices' => [
                    'Top' => 'top',
                    'Bottom' => 'bottom',
                    'Background' => 'background',
                    'Background fitted' => 'background-fitted',
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => false,
            ])
            ->add('image', UploadType::class, [
                'label' => 'Please select the image file you want to upload.',
                'required' => false,
            ])
            ->add('text', TextareaType::class, [
                'required' => false,
                'label' => 'Please insert the text you want to show.',
                'attr' => [
                    'class' => 'ckeditor',
                ],
            ])
            ->add('buttonText', TextType::class, [
                'label' => 'Button Text',
                'required' => false,
            ])
            ->add('page', DoctrineType::class, [
                'required' => false,
                'label' => 'Page',
                'entityClass' => Alias::class,
            ])
        ;
    }
}