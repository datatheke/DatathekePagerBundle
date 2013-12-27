<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;

abstract class AbstractHandler implements HttpHandlerInterface
{
    abstract public function handleRequest(HttpDatagridInterface $datagrid, Request $request);

    protected function createJsonResponse($content)
    {
        return new Response(json_encode($content), 200, array('Content-type' => 'application/json'));
    }

    protected function getItems(HttpDatagridInterface $datagrid)
    {
        $items = array();
        foreach ($datagrid->getPager()->getItems() as $row) {
            $item = array();
            foreach ($datagrid->getColumns() as $alias => $column) {
                $item[$alias] = $datagrid->getColumnValue($column, $row);
            }
            $items[] = $item;
        }

        return $items;
    }
}
