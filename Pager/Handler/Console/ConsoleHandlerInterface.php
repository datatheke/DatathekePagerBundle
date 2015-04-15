<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

interface ConsoleHandlerInterface
{
    public function handleConsole(PagerInterface $pager, OutputInterface $output, HelperSet $helperSet);
}
