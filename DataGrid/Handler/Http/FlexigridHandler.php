<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\FlexigridHandler as FlexigridPagerHandler;

class FlexigridHandler extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct()
    {
        parent::__construct();

        $this->pagerHandler = new FlexigridPagerHandler();
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
            'total' => $pager->getTotalItemCount(),
            'rows' => $this->getItems($datagrid)
        );

        return $this->createJsonResponse($request, $response);
    }
}
