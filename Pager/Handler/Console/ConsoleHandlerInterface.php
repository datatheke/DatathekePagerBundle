<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;

use Datatheke\Bundle\PagerBundle\Pager\ConsolePagerInterface;

interface ConsoleHandlerInterface
{
    public function handleConsole(ConsolePagerInterface $pager, OutputInterface $output, HelperSet $helperSet);
}
