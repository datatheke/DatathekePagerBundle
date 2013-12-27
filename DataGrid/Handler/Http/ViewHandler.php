<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDataGridInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\DataGridView;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler as PagerViewHandler;
use Datatheke\Bundle\PagerBundle\Pager\PagerView;

class ViewHandler implements HttpHandlerInterface
{
    protected $pagerHandler;

    public function __construct(array $options = array())
    {
        $this->pagerHandler = new PagerViewHandler($options);
    }

    public function handleRequest(HttpDataGridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();
        $this->pagerHandler->handleRequest($pager, $request);

        return $this->createView($datagrid, $request);
    }

    protected function createView(HttpDataGridInterface $datagrid, Request $request)
    {
        return new DataGridView($datagrid, new PagerView($datagrid->getPager(), $this->pagerHandler));
    }
}
