<?php

namespace RevisionTen\CMS\DependencyInjection;

use RevisionTen\CMS\Form\PageMetaType;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        // Todo: https://symfony.com/blog/new-in-symfony-4-2-important-deprecations?#deprecated-tree-builders-without-root-nodes
        $rootNode = $treeBuilder->root('cms');
        $rootNode
            ->children()
                ->scalarNode('site_name')
                    ->info('The name of your website')
                    ->defaultValue('RevisionTen CMS')
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
                ->scalarNode('page_metatype')
                    ->info('The form type class for the page settings.')
                    ->defaultValue(PageMetaType::class)
                ->end()
                ->arrayNode('page_elements')
                    ->info('Defines all element that are available in the page editor.')
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
                    ->end()
                ->end()
                ->arrayNode('menu_items')
                    ->info('Defines all items that are available in the menu editor.')
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
                    ->end()
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
                ->arrayNode('page_templates')
                    ->info('Defines the page template the user can choose from.')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('template')->end()
                            ->scalarNode('metatype')->end()
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
            ->end();

        return $treeBuilder;
    }
}
