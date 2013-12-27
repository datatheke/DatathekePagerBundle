<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;

interface ConsoleDataGridInterface extends DataGridInterface
{
    public function handleConsole(OutputInterface $output, HelperSet $helperSet);
}
