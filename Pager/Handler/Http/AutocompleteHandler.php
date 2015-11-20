<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutocompleteHandler extends AbstractHandler
{
    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'search_param' => 'term',
            'search_fields' => null,
            )
        );
    }

    public function setSearchParameter($searchParameter)
    {
        $this->options['search_param'] = $searchParameter;

        return $this;
    }

    public function setSearchFields(array $searchFields = null)
    {
        $this->options['search_fields'] = $searchFields;

        return $this;
    }

    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($this->has($request, $this->options['search_param'])) {
            $this->search($pager, $this->get($request, $this->options['search_param']), $this->options['search_fields']);
        }
    }
}
