<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Datatheke\Bundle\PagerBundle\Pager\HttpPagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class jqGridHandler implements HttpHandlerInterface
{
    public function handleRequest(HttpPagerInterface $pager, Request $request)
    {
        if ($request->query->has('page')) {
            $pager->setCurrentPageNumber($request->query->get('page'));
        }

        if ($request->query->has('rows')) {
            $pager->setItemCountPerPage($request->query->get('rows'));
        }

        $fields = $pager->getFields();
        if (($request->query->has('sidx')) && isset($fields[$field = $request->query->get('sidx')])) {
            $order = $request->query->get('sord', 'asc') === 'asc' ? OrderBy::ASC : OrderBy::DESC;
            $pager->setOrderBy(new OrderBy(array($field => $order)));
        }

        $response = array(
            'page' => $pager->getCurrentPageNumber(),
            'total' => $pager->getPageCount(),
            'records' => $pager->getTotalItemCount(),
            'rows' => $pager->getItems()
        );

        return new Response(json_encode($response), 200, array('Content-type' => 'application/json'));
    }
}