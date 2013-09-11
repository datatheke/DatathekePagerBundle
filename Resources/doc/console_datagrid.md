``` php
<?php

namespace Datatheke\Bundle\DemoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataGridEntityCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('demo:datagrid_entity')
            ->setDescription('Test datagrid in console')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $datagrid = $this->getContainer()->get('datatheke.datagrid')->createConsoleDataGrid('DatathekeDemoBundle:Country', array(
            'item_count_per_page' => 3,
            // 'interactive' => false
            )
        );

        $datagrid->handleConsole($output, $this->getHelperSet());
    }
}
```