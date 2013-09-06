<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Action;

use Datatheke\Bundle\PagerBundle\DataGrid\Datagrid;

interface ActionInterface
{
    public function getLabel();

    public function getRoute();

    public function getParameters(Datagrid $datagrid, $item);

    public function evaluateDisplay(Datagrid $datagrid, $item);

    public function getType();
}
