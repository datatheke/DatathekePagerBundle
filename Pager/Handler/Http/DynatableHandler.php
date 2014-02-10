<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class DynatableHandler extends AbstractHandler
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($this->has($request, 'page')) {
            $pager->setCurrentPageNumber($this->get($request, 'page'));
        }

        if ($this->has($request, 'perPage')) {
            $pager->setItemCountPerPage($this->get($request, 'perPage'));
        }

        $fields = $pager->getFields();
        if ($this->has($request, 'sorts') && is_array($sort = $this->get($request, 'sorts'))) {
            $field = key($sort);
            $order = current($sort) > 0 ? OrderBy::ASC : OrderBy::DESC;

            if (isset($fields[$field])) {
                $pager->setOrderBy(new OrderBy(array($field => $order)));
            }
        }

        if ($this->has($request, 'queries') && is_array($query = $this->get($request, 'queries'))) {
            $this->search($pager, current($query));
        }
    }
}
