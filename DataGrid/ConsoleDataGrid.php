<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\Pager\ConsolePager;

class ConsoleDataGrid extends DataGrid
{
    protected $options;

    public function __construct(ConsolePager $pager, array $columns = null, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        parent::__construct($pager, $columns);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'interactive' => true
            )
        );
    }

    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    public function getOption($option)
    {
        if (!$this->hasOption($option)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $option));
        }

        return $this->options[$option];
    }

    public function handleConsole(OutputInterface $output, HelperSet $helperSet)
    {
        $this->initialize();

        $interactive = $this->options['interactive'];
        do {
            $this->renderPager($output, $helperSet);

            if ($interactive) {
                $interactive = $this->pager->handleInput($output, $helperSet);
            }

        } while ($interactive);
    }

    protected function renderPager(OutputInterface $output, HelperSet $helperSet)
    {
        $table     = $helperSet->get('table');
        $formatter = $helperSet->get('formatter');

        $output->writeln($formatter->formatBlock(
            array(
                'Page:     '.$this->pager->getCurrentPageNumber().' / '.$this->pager->getPageCount(),
                'Items:    '.$this->pager->getFirstItemNumber().'-'.$this->pager->getLastItemNumber().' / '.$this->pager->getTotalItemCount()
            ),
            'bg=blue;fg=white'
        ));

        $items = array();
        foreach ($this->pager->getItems() as $item) {
            $row = array();
            foreach ($this->getColumns() as $column) {
                $row[] = $this->getColumnValue($column, $item);
            }
            $items[] = $row;
        }

        $headers = array();
        foreach ($this->getColumns() as $column) {
            $headers[] = $column->getLabel();
        }

        $table
            ->setHeaders($headers)
            ->setRows($items)
            ->render($output)
        ;
    }
}
