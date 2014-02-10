<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\AutocompleteHandler as AutocompletePagerHandler;

class AutocompleteHandler extends AbstractHandler
{
    protected $pagerHandler;

    public function __construct(array $options = array())
    {
        $this->pagerHandler = new AutocompletePagerHandler($options);
    }

    public function setMethod($method)
    {
        $this->pagerHandler->setMethod($method);

        return $this;
    }

    public function setSearchParameter($searchParameter)
    {
        $this->pagerHandler->setSearchParameter($searchParameter);

        return $this;
    }

    public function setSearchFields($searchFields)
    {
        $this->pagerHandler->setSearchFields($searchFields);

        return $this;
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
