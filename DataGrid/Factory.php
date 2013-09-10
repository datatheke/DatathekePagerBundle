<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Datatheke\Bundle\PagerBundle\Pager\Pager;
use Datatheke\Bundle\PagerBundle\Pager\Factory as PagerFactory;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\GuesserInterface;

class Factory
{
    protected $config;
    protected $pagerFactory;
    protected $guesser;

    public function __construct(Configuration $config, PagerFactory $pagerFactory, GuesserInterface $guesser)
    {
        $this->config       = $config;
        $this->pagerFactory = $pagerFactory;
        $this->guesser      = $guesser;
    }

    public function createWebDataGrid($pager, array $options = array(), array $columns = null)
    {
        if (!$pager instanceOf Pager) {
            $pager = $this->pagerFactory->createWebPager($pager);
        }

        if (null == $columns) {
            $columns = $this->guessColumns($pager->getAdapter()->getFields());
        }

        return new WebDataGrid($pager, $columns, $options);
    }

    public function createConsoleDataGrid($pager, array $options = array(), array $columns = null)
    {
        if (!$pager instanceOf Pager) {
            $pager = $this->pagerFactory->createConsolePager($pager);
        }

        if (null == $columns) {
            $columns = $this->guessColumns($pager->getAdapter()->getFields());
        }

        return new ConsoleDataGrid($pager, $columns, $options);
    }

    protected function guessColumns(array $fields)
    {
        $columns = array();
        foreach ($fields as $fieldAlias => $field) {
            $columns[$fieldAlias] = $this->guesser->guess($field, $fieldAlias);
        }

        return $columns;
    }
}
