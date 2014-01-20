<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Datatheke\Bundle\PagerBundle\DataGrid\ConsoleDataGridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Console\DefaultHandler as PagerDefaultHandler;

class DefaultHandler implements ConsoleHandlerInterface
{
    protected $handler;

    public function __construct(array $options = array())
    {
        $this->handler = new PagerDefaultHandler($options);
    }

    public function setInteractive($interactive)
    {
        $this->handler->setInteractive($interactive);
    }

    public function handleConsole(ConsoleDataGridInterface $datagrid, OutputInterface $output, HelperSet $helperSet)
    {
        $interactive = $this->handler->isInteractive();
        do {
            $this->createView($datagrid, $output, $helperSet);

            if ($interactive) {
                $interactive = $this->handler->handleInput($datagrid->getPager(), $output, $helperSet);
            }
        } while ($interactive);
    }

    protected function createView(ConsoleDataGridInterface $datagrid, OutputInterface $output, HelperSet $helperSet)
    {
        $pager     = $datagrid->getPager();

        $table     = $helperSet->get('table');
        $formatter = $helperSet->get('formatter');

        $output->writeln($formatter->formatBlock(
            array(
                'Page:     '.$pager->getCurrentPageNumber().' / '.$pager->getPageCount(),
                'Items:    '.$pager->getFirstItemNumber().'-'.$pager->getLastItemNumber().' / '.$pager->getTotalItemCount()
            ),
            'bg=blue;fg=white'
        ));

        $items = array();
        foreach ($pager->getItems() as $item) {
            $row = array();
            foreach ($datagrid->getColumns() as $column) {
                $row[] = $datagrid->getColumnValue($column, $item);
            }
            $items[] = $row;
        }

        $headers = array();
        foreach ($datagrid->getColumns() as $column) {
            $headers[] = $column->getLabel();
        }

        $table
            ->setHeaders($headers)
            ->setRows($items)
            ->render($output)
        ;
    }
}
