<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Console;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleHandlerInterface
{
    public function handleConsole(PagerInterface $pager, OutputInterface $output, HelperSet $helperSet);
}
