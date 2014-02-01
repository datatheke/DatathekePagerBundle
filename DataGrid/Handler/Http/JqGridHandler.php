<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\JqGridHandler as jqGridPagerHandler;

class JqGridHandler extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct()
    {
        $this->pagerHandler = new JqGridPagerHandler();
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
            'page' => $pager->getCurrentPageNumber(),
            'total' => $pager->getPageCount(),
            'records' => $pager->getTotalItemCount(),
            'rows' => $this->getItems($datagrid)
        );

        return $this->createJsonResponse($response);
    }
}
