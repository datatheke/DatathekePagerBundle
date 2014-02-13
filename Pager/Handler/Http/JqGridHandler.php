<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class JqGridHandler extends AbstractHandler
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($this->has($request, 'page')) {
            $pager->setCurrentPageNumber($this->get($request, 'page'));
        }

        if ($this->has($request, 'rows')) {
            $pager->setItemCountPerPage($this->get($request, 'rows'));
        }

        $fields = $pager->getFields();
        if ($this->has($request, 'sidx') && isset($fields[$field = $this->get($request, 'sidx')])) {
            $order = $this->get($request, 'sord', 'asc') === 'asc' ? OrderBy::ASC : OrderBy::DESC;
            $pager->setOrderBy(new OrderBy(array($field => $order)));
        }
    }
}
