<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class JqGridHandler implements HttpHandlerInterface
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($request->query->has('page')) {
            $pager->setCurrentPageNumber($request->query->get('page'));
        }

        if ($request->query->has('rows')) {
            $pager->setItemCountPerPage($request->query->get('rows'));
        }

        $fields = $pager->getFields();
        if ($request->query->has('sidx') && isset($fields[$field = $request->query->get('sidx')])) {
            $order = $request->query->get('sord', 'asc') === 'asc' ? OrderBy::ASC : OrderBy::DESC;
            $pager->setOrderBy(new OrderBy(array($field => $order)));
        }
    }
}
