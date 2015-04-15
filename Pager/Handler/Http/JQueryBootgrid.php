<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;

class JQueryBootgrid extends AbstractHandler
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'method' => 'request',
            'search_fields' => null,
            'item_count_limit' => 1000,
            )
        );
    }

    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($this->has($request, 'current')) {
            $pager->setCurrentPageNumber($this->get($request, 'current'));
        }

        if ($this->has($request, 'rowCount')) {
            $rowCount = $this->get($request, 'rowCount');
            if ($rowCount < 0) {
                $rowCount = $this->options['item_count_limit'];
            }

            $pager->setItemCountPerPage($rowCount);
        }

        if ($this->has($request, 'sort')) {
            $sort = $this->get($request, 'sort');
            if (is_array($sort)) {
                $pager->setOrderBy(new OrderBy($sort));
            }
        }

        if ($this->has($request, 'searchPhrase')) {
            $this->search($pager, $this->get($request, 'searchPhrase'), $this->options['search_fields']);
        }
    }
}
