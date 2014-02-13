<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BootstrapTypeaheadHandler extends AutocompleteHandler
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'search_param'  => 'query'
            )
        );
    }
}
