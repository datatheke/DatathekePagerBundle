<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsolePagerInterface extends PagerInterface
{
    public function handleConsole(OutputInterface $output, HelperSet $helperSet);
}
