<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;

use Datatheke\Bundle\PagerBundle\DataGrid\ConsoleDataGridInterface;

interface ConsoleHandlerInterface
{
    public function handleConsole(ConsoleDataGridInterface $datagrid, OutputInterface $output, HelperSet $helperSet);
}
