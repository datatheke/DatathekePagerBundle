<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\DataTablesHandler as DataTablesPagerHandler;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class DataTablesHandler extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct()
    {
        parent::__construct();

        $this->pagerHandler = new DataTablesPagerHandler();
    }

    public function handleRequest(HttpDatagridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();
        $this->pagerHandler->handleRequest($pager, $request);

        // Sort
        if ($this->has($request, 'iSortCol_0')) {
            $columns = array_keys($datagrid->getColumns());
            $column  = $columns[$this->get($request, 'iSortCol_0')];

            $order = $this->get($request, 'sSortDir_0') === 'asc' ? OrderBy::ASC : OrderBy::DESC;
            $datagrid->getPager()->setOrderBy(new OrderBy(array($column => $order)));
        }

        return $this->createView($datagrid, $request);
    }

    protected function createView(HttpDatagridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();

        $columns = array();
        foreach ($datagrid->getColumns() as $alias => $column) {
            $columns[$alias] = $column->getLabel();
        }

        $response = array(
            'sEcho' => $this->get($request, 'sEcho'),
            'iTotalDisplayRecords' => $pager->getTotalItemCount(),
            'iTotalRecords' => $pager->getTotalItemCount(),
            'columns' => $columns,
            'aaData' => $this->getItems($datagrid, true)
        );

        return $this->createJsonResponse($request, $response);
    }
}
