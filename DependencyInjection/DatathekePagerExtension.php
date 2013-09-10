<?php

namespace Datatheke\Bundle\PagerBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class DatathekePagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('pager.xml');
        $loader->load('datagrid.xml');
        $loader->load('twig.xml');

        $container->setParameter('datatheke.pager.item_count_per_page', $config['item_count_per_page']);
        $container->setParameter('datatheke.pager.item_count_per_page_choices', $config['item_count_per_page_choices']);
        $container->setParameter('datatheke.pager.page_range', $config['page_range']);

        $container->setParameter('datatheke.datagrid.theme', $config['datagrid_theme']);
    }
}
