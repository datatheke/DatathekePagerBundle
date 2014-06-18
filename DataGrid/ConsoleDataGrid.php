<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;

use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console\ConsoleHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

class ConsoleDataGrid extends DataGrid implements ConsoleDataGridInterface
{
    protected $handler;

    public function __construct(PagerInterface $pager, ConsoleHandlerInterface $handler, array $columns)
    {
        $this->handler = $handler;

        parent::__construct($pager, $columns);
    }

    public function handleConsole(OutputInterface $output, HelperSet $helperSet)
    {
        $this->initialize();

        return $this->handler->handleConsole($this, $output, $helperSet);
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
