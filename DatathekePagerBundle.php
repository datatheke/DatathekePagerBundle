<?php

namespace Datatheke\Bundle\PagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler\AdapterGuesserCompilerPass;
use Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler\ColumnGuesserCompilerPass;
use Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler\JMSSerializerCompilerPass;
use Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler\DoctrineMongoDBCompilerPass;
use Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler\DoctrineORMCompilerPass;
use Datatheke\Bundle\PagerBundle\DependencyInjection\Compiler\HandlerCompilerPass;

class DatathekePagerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineMongoDBCompilerPass());
        $container->addCompilerPass(new DoctrineORMCompilerPass());
        $container->addCompilerPass(new JMSSerializerCompilerPass());

        $container->addCompilerPass(new AdapterGuesserCompilerPass());
        $container->addCompilerPass(new ColumnGuesserCompilerPass());
        $container->addCompilerPass(new HandlerCompilerPass());
    }
}
