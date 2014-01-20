<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\GuesserInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\HttpHandlerInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\ViewHandler;
use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console\ConsoleHandlerInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console\DefaultHandler;
use Datatheke\Bundle\PagerBundle\Pager\HttpPagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\ConsolePagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Factory as PagerFactory;

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

    /**
     * @deprecated
     */
    public function createWebDataGrid($pager, array $options = array(), array $columns = null)
    {
        trigger_error('createWebDataGrid() is deprecated. Use createHttpDataGrid() instead.', E_USER_DEPRECATED);

        return $this->createHttpDataGrid($pager, $options, $columns);
    }

    public function createHttpDataGrid($pager, array $options = array(), array $columns = null, $handler = null)
    {
        if (!$pager instanceOf HttpPagerInterface) {
            $pager = $this->pagerFactory->createHttpPager($pager);
        }

        if (!$handler instanceOf HttpHandlerInterface) {
            $handler = new ViewHandler();
        }

        if (null === $columns) {
            $columns = $this->guessColumns($pager->getFields());
        }

        return new HttpDataGrid($pager, $handler, $columns, $options);
    }

    public function createConsoleDataGrid($pager, array $options = array(), array $columns = null, $handler = null)
    {
        if (!$pager instanceOf ConsolePagerInterface) {
            $pager = $this->pagerFactory->createConsolePager($pager);
        }

        if (!$handler instanceOf ConsoleHandlerInterface) {
            $handler = new DefaultHandler();
        }

        if (null === $columns) {
            $columns = $this->guessColumns($pager->getFields());
        }

        return new ConsoleDataGrid($pager, $handler, $columns, $options);
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
