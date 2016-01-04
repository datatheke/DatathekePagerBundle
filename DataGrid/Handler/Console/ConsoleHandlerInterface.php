<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console;

use Datatheke\Bundle\PagerBundle\DataGrid\ConsoleDataGridInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleHandlerInterface
{
    public function handleConsole(ConsoleDataGridInterface $datagrid, OutputInterface $output, HelperSet $helperSet);
}
