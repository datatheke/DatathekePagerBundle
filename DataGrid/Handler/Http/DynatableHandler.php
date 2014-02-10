<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\DynatableHandler as DynatablePagerHandler;

class DynatableHandler extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct()
    {
        parent::__construct();

        $this->pagerHandler = new DynatablePagerHandler();
    }

    public function handleRequest(HttpDatagridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();
        $this->pagerHandler->handleRequest($pager, $request);

        return $this->createView($datagrid, $request);
    }

    protected function createView(HttpDatagridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();

        $response = array(
            'queryRecordCount' => $pager->getTotalItemCount(),
            'totalRecordCount' => $pager->getCurrentItemCount(),
            'records'          => $this->getItems($datagrid)
        );

        return $this->createJsonResponse($request, $response);
    }
}
