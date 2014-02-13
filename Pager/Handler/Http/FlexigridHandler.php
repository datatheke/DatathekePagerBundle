<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class FlexigridHandler extends AbstractHandler
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'method' => 'request'
            )
        );
    }

    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($this->has($request, 'page')) {
            $pager->setCurrentPageNumber($this->get($request, 'page'));
        }

        if ($this->has($request, 'rp')) {
            $pager->setItemCountPerPage($this->get($request, 'rp'));
        }

        $fields = $pager->getFields();
        if ($this->has($request, 'sortname') && isset($fields[$field = $this->get($request, 'sortname')])) {
            $order = $this->get($request, 'sortorder', 'asc') === 'asc' ? OrderBy::ASC : OrderBy::DESC;
            $pager->setOrderBy(new OrderBy(array($field => $order)));
        }

        if ($this->has($request, 'qtype') && isset($fields[$field = $this->get($request, 'qtype')]) && $this->has($request, 'query')) {
            $operator = Field::TYPE_STRING === $fields[$field]->getType() ? Filter::OPERATOR_CONTAINS : Filter::OPERATOR_EQUALS;
            $pager->setFilter(new Filter(array($field), array($this->get($request, 'query')), array($operator)));
        }
    }
}
