<?php

namespace Datatheke\Bundle\PagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('datatheke_pager');

        $rootNode
            ->children()
                ->integerNode('item_count_per_page')
                    ->defaultValue('10')
                    ->min(1)
                    ->info('Default item count per page')
                ->end()
                ->arrayNode('item_count_per_page_choices')
                    ->info('Choices list for item count per page')
                    ->prototype('integer')->end()
                    ->defaultValue(array(10, 50, 100, 500, 1000))
                ->end()
                ->integerNode('page_range')
                    ->defaultValue('5')
                    ->min(1)
                    ->info('Default page range')
                ->end()
                ->scalarNode('datagrid_theme')
                    ->defaultValue('DatathekePagerBundle:DataGrid:bootstrap3.html.twig')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
