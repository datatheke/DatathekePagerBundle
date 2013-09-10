<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\PropertyAccess\PropertyAccess;

use Datatheke\Bundle\PagerBundle\Datagrid\Column\ColumnInterface;
// use Datatheke\Bundle\PagerBundle\Datagrid\Action\ActionInterface;
use Datatheke\Bundle\PagerBundle\Pager\Pager;

abstract class DataGrid
{
    protected $accessor;
    protected $pager;
    protected $columns;
    // protected $actions = array();

    protected $initialized;

    public function __construct(Pager $pager, array $columns)
    {
        $this->accessor    = PropertyAccess::createPropertyAccessor();
        $this->pager       = $pager;
        $this->columns     = $columns;

        $this->initialized = false;
    }

    public function addColumn(ColumnInterface $column, $alias = null)
    {
        if (null !== $alias) {
            $this->columns[$alias] = $column;
        }
        else {
            $this->columns[] = $column;
        }

        return $this;
    }

    public function getColumns()
    {
        $columns = array();
        foreach ($this->columns as $alias => $column) {
            if ($column->isVisible()) {
                $columns[$alias] = $column;
            }
        }

        return $columns;
    }

    public function getColumn($alias)
    {
        if (!isset($this->columns[$alias])) {
            throw new \InvalidArgumentException(sprintf('The "%s" column does not exist.', $alias));
        }

        return $this->columns[$alias];
    }

    public function sortColumns(array $order)
    {
        $sorted = array();
        foreach ($order as $alias) {
            $sorted[$alias] = $this->getColumn($alias);
        }

        foreach ($this->columns as $alias => $column) {
            if (!isset($sorted[$alias])) {
                $sorted[$alias] = $column;
            }
        }
        $this->columns = $sorted;

        return $this;
    }

    public function getPager()
    {
        return $this->pager;
    }

    // public function addAction(ActionInterface $action, $alias = null)
    // {
    //     if (null !== $alias) {
    //         $this->actions[$alias] = $action;
    //     }
    //     else {
    //         $this->actions[] = $action;
    //     }

    //     return $this;
    // }

    // public function hasActions()
    // {
    //     return count($this->actions) > 0;
    // }

    // public function getActions()
    // {
    //     return $this->actions;
    // }

    // public function getAction($alias)
    // {
    //     return $this->actions[$alias];
    // }

    public function getColumnValue(ColumnInterface $column, $item)
    {
        if (null === $column->getField()) {
            return null;
        }
        $value  = $this->accessor->getValue($item, $column->getField()->getPropertyPath());

        return $column->format($value);
    }

    protected function initialize()
    {
        if (true === $this->initialized) {
            return;
        }

        foreach ($this->columns as $column) {
            $column->initialize();
        }

        $this->initialized = true;
    }
}
