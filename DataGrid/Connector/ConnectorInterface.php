<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Connector;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\DataGrid;

interface ConnectorInterface
{
    public function handle(DataGrid $datagrid, Request $request);
}
