<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class DynatableHandler implements HttpHandlerInterface
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($request->query->has('page')) {
            $pager->setCurrentPageNumber($request->query->get('page'));
        }

        if ($request->query->has('perPage')) {
            $pager->setItemCountPerPage($request->query->get('perPage'));
        }

        $fields = $pager->getFields();
        if ($request->query->has('sorts') && is_array($sort = $request->query->get('sorts'))) {
            $field = key($sort);
            $order = current($sort) > 0 ? OrderBy::ASC : OrderBy::DESC;

            if (isset($fields[$field])) {
                $pager->setOrderBy(new OrderBy(array($field => $order)));
            }
        }

        if ($request->query->has('queries') && is_array($query = $request->query->get('queries'))) {
            $term  = current($query);

            $filter = array('operator' => Filter::LOGICAL_OR, 'criteria' => array());
            foreach ($fields as $alias => $field) {
                $operator = Field::TYPE_STRING === $field->getType() ? Filter::OPERATOR_CONTAINS : Filter::OPERATOR_EQUALS;

                $filter['criteria'][] = array(
                    'field'    => $alias,
                    'operator' => $operator,
                    'value'    => $term
                    );
            }
            $pager->setFilter(Filter::createFromArray($filter));
        }
    }
}
