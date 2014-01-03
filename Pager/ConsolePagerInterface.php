<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;

interface ConsolePagerInterface extends PagerInterface
{
    public function handleConsole(OutputInterface $output, HelperSet $helperSet);
}