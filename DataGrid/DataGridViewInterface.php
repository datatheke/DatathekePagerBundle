<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Datatheke\Bundle\PagerBundle\Datagrid\Column\ColumnInterface;

interface DataGridViewInterface
{
    public function getPager();

    public function getColumns();

    public function getColumn($alias);

    public function getColumnValue(ColumnInterface $column, $item);
}
