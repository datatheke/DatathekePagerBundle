<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\JQueryBootgrid as JQueryBootgridPagerHandler;
use Symfony\Component\HttpFoundation\Request;

class JQueryBootgrid extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct()
    {
        parent::__construct();

        $this->pagerHandler = new JQueryBootgridPagerHandler();
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
            'current' => $pager->getCurrentPageNumber(),
            'rowCount' => $pager->getItemCountPerPage(),
            'total' => $pager->getTotalItemCount(),
            'rows' => $this->getItems($datagrid),
        );

        return $this->createJsonResponse($request, $response);
    }
}
