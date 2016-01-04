<?php

namespace Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class DoctrineMongoDBCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('doctrine_mongodb')) {
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));
            $loader->load('doctrine_mongodb.xml');
        }
    }
}
