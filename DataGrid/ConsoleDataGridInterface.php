<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleDataGridInterface extends DataGridInterface
{
    public function handleConsole(OutputInterface $output, HelperSet $helperSet);
}
