<?php

namespace Gao\C5Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gao_c5');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        // Paging module.
        $rootNode
                ->children()
                    ->arrayNode('paging')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('defaults')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->integerNode('items_limit_per_page')->defaultValue(30)->cannotBeEmpty()->end()
                            ->integerNode('pages_limit_in_range')->defaultValue(5)->cannotBeEmpty()->end()
                            ->scalarNode('template')->defaultValue('GaoC5Bundle:mod/Paging:default.html.twig')->end()
                        ->end()
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }
}
