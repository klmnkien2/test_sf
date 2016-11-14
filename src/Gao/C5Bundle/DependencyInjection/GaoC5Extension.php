<?php

namespace Gao\C5Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GaoC5Extension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Paging module.
        $container->setParameter('paging.defaults.items_limit_per_page', $config['paging']['defaults']['items_limit_per_page']);
        $container->setParameter('paging.defaults.pages_limit_in_range', $config['paging']['defaults']['pages_limit_in_range']);
        $container->setParameter('paging.defaults.template', $config['paging']['defaults']['template']);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.yml');
    }
}
