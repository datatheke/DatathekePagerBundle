<?php

namespace Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('datatheke.datagrid.factory')) {
            $this->findDataGridHandlers($container);
        }

        if ($container->hasDefinition('datatheke.pager.factory')) {
            $this->findPagerHandlers($container);
        }
    }

    protected function findDataGridHandlers(ContainerBuilder $container)
    {
        $handlers = array();
        foreach ($container->findTaggedServiceIds('datatheke_pager.datagrid_http_handler') as $id => $attributes) {
            $handlers[$attributes[0]['alias']] = new Reference($id);
        }
        $container->getDefinition('datatheke.datagrid.factory')->replaceArgument(3, $handlers);

        $handlers = array();
        foreach ($container->findTaggedServiceIds('datatheke_pager.datagrid_console_handler') as $id => $attributes) {
            $handlers[$attributes[0]['alias']] = new Reference($id);
        }
        $container->getDefinition('datatheke.datagrid.factory')->replaceArgument(4, $handlers);
    }

    protected function findPagerHandlers(ContainerBuilder $container)
    {
        $handlers = array();
        foreach ($container->findTaggedServiceIds('datatheke_pager.pager_http_handler') as $id => $attributes) {
            $handlers[$attributes[0]['alias']] = new Reference($id);
        }
        $container->getDefinition('datatheke.pager.factory')->replaceArgument(2, $handlers);

        $handlers = array();
        foreach ($container->findTaggedServiceIds('datatheke_pager.pager_console_handler') as $id => $attributes) {
            $handlers[$attributes[0]['alias']] = new Reference($id);
        }
        $container->getDefinition('datatheke.pager.factory')->replaceArgument(3, $handlers);
    }
}
