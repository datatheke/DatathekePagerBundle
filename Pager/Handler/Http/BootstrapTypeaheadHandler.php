<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\Field;

class BootstrapTypeaheadHandler implements HttpHandlerInterface
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($request->query->has('query')) {
            $query = $request->query->get('query');
            $filter = array('operator' => Filter::LOGICAL_OR, 'criteria' => array());
            foreach ($pager->getFields() as $alias => $field) {
                $operator = Field::TYPE_STRING === $field->getType() ? Filter::OPERATOR_CONTAINS : Filter::OPERATOR_EQUALS;

                $filter['criteria'][] = array(
                    'field'    => $alias,
                    'operator' => $operator,
                    'value'    => $query
                    );
            }

            $pager->setFilter(Filter::createFromArray($filter));
        }
    }
}
