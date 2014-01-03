<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\HttpHandlerInterface;

class HttpPager extends Pager implements HttpPagerInterface
{
    protected $handler;

    public function __construct(AdapterInterface $adapter, HttpHandlerInterface $handler, array $options = array())
    {
        $this->handler = $handler;

        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        parent::__construct($adapter, $this->options['item_count_per_page'], $this->options['item_count_per_page_choices']);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'item_count_per_page',
            )
        );

        $resolver->setDefaults(array(
            'item_count_per_page_choices' => array()
            )
        );
    }

    public function handleRequest(Request $request)
    {
        return $this->handler->handleRequest($this, $request);
    }
}