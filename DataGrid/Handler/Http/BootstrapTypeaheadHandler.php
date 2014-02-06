<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\BootstrapTypeaheadHandler as BootstrapTypeaheadPagerHandler;

class BootstrapTypeaheadHandler extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct()
    {
        $this->pagerHandler = new BootstrapTypeaheadPagerHandler();
    }

    public function handleRequest(HttpDatagridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();
        $this->pagerHandler->handleRequest($pager, $request);

        return $this->createView($datagrid, $request);
    }

    protected function createView(HttpDatagridInterface $datagrid, Request $request)
    {
        return $this->createJsonResponse($this->getItems($datagrid));
    }
}
