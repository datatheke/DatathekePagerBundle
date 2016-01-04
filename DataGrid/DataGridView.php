<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Datatheke\Bundle\PagerBundle\Datagrid\Column\ColumnInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerViewInterface;

class DataGridView implements DataGridViewInterface
{
    protected $datagrid;
    protected $pagerView;

    public function __construct(DataGridInterface $datagrid, PagerViewInterface $pagerView)
    {
        $this->datagrid = $datagrid;
        $this->pagerView = $pagerView;
    }

    public function getPager()
    {
        return $this->pagerView;
    }

    public function getColumn($alias)
    {
        return $this->datagrid->getColumn($alias);
    }

    public function getColumns()
    {
        return $this->datagrid->getColumns();
    }

    public function getColumnValue(ColumnInterface $column, $item)
    {
        return $this->datagrid->getColumnValue($column, $item);
    }

    public function setRoute($route)
    {
        $this->pagerView->setRoute($route);

        return $this;
    }

    public function setParameters($parameters)
    {
        $this->pagerView->setParameters($parameters);

        return $this;
    }
}
