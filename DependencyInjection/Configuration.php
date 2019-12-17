<?php

declare(strict_types=1);

namespace RevisionTen\CMS\DependencyInjection;

use RevisionTen\CMS\Form\PageMetaType;
use RevisionTen\CMS\Form\PageType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return ArrayNodeDefinition
     */
    public function addPageTemplatesNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('page_templates');

        $node = $treeBuilder->getRootNode()
            ->info('Defines the page template the user can choose from.')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('template')->end()
                    ->scalarNode('metatype')->end()
                    ->scalarNode('solr_serializer')->end()
                    ->arrayNode('alias_prefix')
                        ->info('Defines the path prefix for pages with this template')
                        ->scalarPrototype()->end()
                    ->end()
                    ->arrayNode('websites')
                        ->info('Defines on which website this template is available')
                        ->integerPrototype()->end()
                    ->end()
                    ->arrayNode('permissions')
                        ->info('Permissions for this page template.')
                        ->children()
                            ->scalarNode('list')->end()
                            ->scalarNode('search')->end()
                            ->scalarNode('new')->end()
                            ->scalarNode('edit')->end()
                            ->scalarNode('delete')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

         return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    public function addPageElementsNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('page_elements');

        $node = $treeBuilder->getRootNode()
            ->info('Defines all element that are available in the page editor.')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('class')
                        ->info('The form type class of the element.')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('type')
                        ->info('Optional property, defines how the element behaves in the frontend editor.')
                    ->end()
                    ->scalarNode('template')
                        ->info('The template of the element.')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('form_template')
                        ->info('The form template of the elements form type.')
                    ->end()
                    ->scalarNode('icon')
                        ->info('A fontawesome icon CSS class to represent the element.')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->booleanNode('public')
                        ->info('Set this to true to make this element available to all elements that accept all child elements.')
                        ->defaultFalse()
                    ->end()
                    ->arrayNode('children')
                        ->info('The type of children elements this element accepts, add "all" to accept all public elements.')
                        ->scalarPrototype()->end()
                    ->end()
                    ->arrayNode('styles')
                        ->info('CSS classes this element can have assigned in its settings.')
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end();

         return $node;
    }

    /**
     * @return ArrayNodeDefinition
     */
    public function addMenuItemsNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('menu_items');

        $node = $treeBuilder->getRootNode()
            ->info('Defines all items that are available in the menu editor.')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('class')
                        ->info('The FormType class of the item.')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('template')
                        ->info('The template of the item.')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end();

         return $node;
    }

    /**
     * {@inheritdoc}
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cms');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->append($this->addPageTemplatesNode())
                ->append($this->addPageElementsNode())
                ->append($this->addMenuItemsNode())
                ->scalarNode('site_name')
                    ->info('The name of your website')
                    ->defaultValue('RevisionTen CMS')
                ->end()
                ->booleanNode('disable_cache_workaround')
                    ->info('Set it to true If your hosts APCu cache behaves properly and shares the cache as one would expect')
                    ->defaultFalse()
                ->end()
                ->integerNode('shm_key')->setDeprecated()->end()
                ->booleanNode('use_mail_codes')
                    ->info('Set this to true If you want to receive login codes by mail, set this to false to use Google Authenticator.')
                    ->defaultFalse()
                ->end()
                ->integerNode('mail_code_lifetime')
                    ->info('Minutes until mailed code expires.')
                    ->defaultValue(5)
                ->end()
                ->scalarNode('solr_host')
                    ->info('The host of the solr instance.')
                    ->defaultValue('localhost')
                ->end()
                ->integerNode('solr_port')
                    ->info('The port of the solr instance.')
                    ->defaultValue(8983)
                ->end()
                ->scalarNode('solr_collection')
                    ->info('The solr collection name.')
                    ->defaultNull()
                ->end()
                ->scalarNode('mailer_from')
                    ->info('From email-address for user management.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('mailer_sender')
                    ->info('Sender email-address for user management.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('mailer_return_path')
                    ->info('Return-path email-address for user management.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('ckeditor_config')
                    ->performNoDeepMerging()
                    ->info('CKEditor configuration')
                    ->normalizeKeys(false)
                    ->ignoreExtraKeys(false)
                ->end()
                ->scalarNode('page_type')
                    ->info('The form type class for the page.')
                    ->defaultValue(PageType::class)
                ->end()
                ->scalarNode('page_metatype')
                    ->info('The form type class for the page settings.')
                    ->defaultValue(PageMetaType::class)
                ->end()
                ->arrayNode('page_languages')
                    ->info('Defines all available languages.')
                    ->defaultValue([
                        'English' => "'en'",
                        'German' => "'de'",
                        'French' => "'fr'",
                    ])
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('page_menues')
                    ->setDeprecated('"page_menues" is deprecated, use "menus" instead.')
                    ->info('Defines the menus.')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('template')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('menus')
                    ->info('Defines the menus.')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('template')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('controller')
                    ->info('Defines the available controller actions for the controller element.')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('action')
                                ->info('The fully qualified path to the controller method, for example "RevisionTen\CMS\Controller\SecurityController::loginForm".')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('permissions')
                    ->info('Permissions that can be assigned to roles.')
                    ->arrayPrototype()
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('name')->end()
                                ->scalarNode('label')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('admin_menu')
                    ->info('Add menu items to the admin menu.')
                    ->children()
                        ->arrayNode('Content')
                            ->normalizeKeys(false)
                            ->defaultValue([])
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('Structure')
                            ->normalizeKeys(false)
                            ->defaultValue([])
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('Settings')
                            ->normalizeKeys(false)
                            ->defaultValue([])
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
