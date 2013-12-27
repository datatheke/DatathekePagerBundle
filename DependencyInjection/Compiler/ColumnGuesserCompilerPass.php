<?php

namespace Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ColumnGuesserCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('datatheke.datagrid.column.guesser_chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'datatheke.datagrid.column.guesser_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'datatheke_pager.column_guesser'
        );

        $guessers = array();
        foreach ($taggedServices as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
            $guessers[$priority] = $id;
        }

        krsort($guessers);
        foreach ($guessers as $id) {
            $definition->addMethodCall(
                'addGuesser',
                array(new Reference($id))
            );
        }
    }
}
