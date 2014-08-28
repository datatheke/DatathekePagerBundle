<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\Filter;

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

        if ($this->has($request, 'filters')) {
            $filters = $this->get($request, 'filters');
            if (!is_array($filters)) {
                if (null === ($filters = json_decode($filters, true))) {
                    $filters = array();
                }
            }

            if (isset($filters['rules']) && isset($filters['groupOp'])) {
                $rules = $filters['rules'];
                $groupOp = (($filters['groupOp'] == 'OR') ? Filter::LOGICAL_OR : Filter::LOGICAL_AND);

                // map filter operators to jqGrid sOpts
                $opMap = array(
                        'eq' => Filter::OPERATOR_EQUALS,
                        'ne' => Filter::OPERATOR_NOT_EQUALS,
                        'lt' => Filter::OPERATOR_LESS,
                        'le' => Filter::OPERATOR_LESS_EQUALS,
                        'gt' => Filter::OPERATOR_GREATER,
                        'ge' => Filter::OPERATOR_GREATER_EQUALS,
                        'bw' => null,
                        'bn' => null,
                        'in' => Filter::OPERATOR_IN,
                        'ni' => Filter::OPERATOR_NOT_IN,
                        'ew' => null,
                        'en' => null,
                        'cn' => Filter::OPERATOR_CONTAINS,
                        'nc' => Filter::OPERATOR_NOT_CONTAINS,
                        'nu' => Filter::OPERATOR_NULL,
                        'nn' => Filter::OPERATOR_NOT_NULL
                    );

                $searchFields = array();
                $searchData = array();
                $searchOperators = array();
                foreach ($rules as $r) {
                    $searchFields[] = $r['field'];
                    $searchData[] = $r['data'];
                    $searchOperators[] = $opMap[ $r['op'] ];
                }

                $pager->setFilter(new Filter($searchFields, $searchData, array($searchOperators), array(array(array($groupOp, null))) ));
            }
        }
    }
}
