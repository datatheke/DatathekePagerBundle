<?php

namespace Datatheke\Bundle\PagerBundle\Connector\DataGrid;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\DataGrid;

class DataTablesConnector implements ConnectorInterface
{
    public function handle(DataGrid $datagrid, Request $request)
    {
        $itemCountPerPage      = $request->get('iDisplayLength', 10);
        $currentPagePageNumber = $request->get('iDisplayStart', 10);
    }
}
