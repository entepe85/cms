<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Form\Settings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class SpacingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('breakpoint', ChoiceType::class, [
            'label' => 'Breakpoint',
            'required' => true,
            'choices' => [
                'xs' => 'xs',
                'sm' => 'sm',
                'md' => 'md',
                'lg' => 'lg',
                'xl' => 'xl',
            ],
            'placeholder' => 'Breakpoint',
            'attr' => [
                'class' => 'custom-select',
            ],
        ]);

        $this->spacingForm($builder, 'top', 'Top');
        $this->spacingForm($builder, 'right', 'Right');
        $this->spacingForm($builder, 'bottom', 'Bottom');
        $this->spacingForm($builder, 'left', 'Left');
    }

    private function spacingForm(FormBuilderInterface $builder, string $key, string $label): void
    {
        $builder->add($key, ChoiceType::class, [
            'label' => $label,
            'required' => false,
            'choices' => [
                'None' => '0',
                'Auto' => 'auto',
                'Extra Small' => '1',
                'Small' => '2',
                'Medium' => '3',
                'Big' => '4',
                'Extra Big' => '5',
                'Huge' => '6',
            ],
            'placeholder' => $label,
            'attr' => [
                'class' => 'custom-select',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'cms_spacing';
    }
}
