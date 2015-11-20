<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\OptionsResolver\OptionsResolver;

class BootstrapTypeaheadHandler extends AutocompleteHandler
{
    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'search_param'  => 'query',
            )
        );
    }
}
