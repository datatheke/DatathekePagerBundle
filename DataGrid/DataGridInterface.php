<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Datatheke\Bundle\PagerBundle\Datagrid\Column\ColumnInterface;

interface DataGridInterface
{
    public function addColumn(ColumnInterface $column, $alias = null);

    public function getColumns();

    public function getColumn($alias);

    public function sortColumns(array $order);

    public function getPager();

    public function getColumnValue(ColumnInterface $column, $item);
}
