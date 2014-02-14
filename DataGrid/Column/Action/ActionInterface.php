<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Action;

use Datatheke\Bundle\PagerBundle\DataGrid\DatagridView;

interface ActionInterface
{
    public function getLabel();

    public function getRoute();

    public function getParameters(DatagridView $datagrid, $item);

    public function evaluateDisplay(DatagridView $datagrid, $item);

    public function getType();
}
