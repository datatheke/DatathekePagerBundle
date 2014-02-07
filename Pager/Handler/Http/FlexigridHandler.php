<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class FlexigridHandler implements HttpHandlerInterface
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($request->request->has('page')) {
            $pager->setCurrentPageNumber($request->request->get('page'));
        }

        if ($request->request->has('rp')) {
            $pager->setItemCountPerPage($request->request->get('rp'));
        }

        $fields = $pager->getFields();
        if ($request->request->has('sortname') && isset($fields[$field = $request->request->get('sortname')])) {
            $order = $request->request->get('sortorder', 'asc') === 'asc' ? OrderBy::ASC : OrderBy::DESC;
            $pager->setOrderBy(new OrderBy(array($field => $order)));
        }

        if ($request->request->has('qtype') && isset($fields[$field = $request->request->get('qtype')]) && $request->request->has('query')) {
            $operator = Field::TYPE_STRING === $fields[$field]->getType() ? Filter::OPERATOR_CONTAINS : Filter::OPERATOR_EQUALS;
            $pager->setFilter(new Filter(array($field), array($request->request->get('query')), array($operator)));
        }
    }
}
