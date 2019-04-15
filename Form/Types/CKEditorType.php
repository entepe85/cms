<?php

declare(strict_types=1);

namespace RevisionTen\CMS\Form\Types;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CKEditorType extends TextareaType
{
    /** @var array */
    protected $config;

    /**
     * CKEditorType constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config['ckeditor_config'] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cms_ckeditor';
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['config'] = array_merge($this->config, $options['config']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'config' => $this->config,
        ]);
    }
}
