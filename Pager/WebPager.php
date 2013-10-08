<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;

class WebPager extends Pager
{
    protected $options;

    public function __construct(AdapterInterface $adapter, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        parent::__construct($adapter, $this->options['item_count_per_page']);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        static $pagerNum;
        $pagerParam = '_p'.$pagerNum++;

        $resolver->setRequired(array(
            'item_count_per_page',
            )
        );

        $resolver->setDefaults(array(
            'pager_param'                 => $pagerParam,
            'order_by_param'              => function (Options $options) { return $options['pager_param'].'[o]'; },
            'filter_param'                => function (Options $options) { return $options['pager_param'].'[f]'; },
            'current_page_number_param'   => function (Options $options) { return $options['pager_param'].'[p]'; },
            'item_count_per_page_param'   => function (Options $options) { return $options['pager_param'].'[pp]'; },

            'item_count_per_page_choices' => array(),
            'route'                       => null,
            'parameters'                  => array(),
            )
        );
    }

    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    public function getOption($option)
    {
        if (!$this->hasOption($option)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $option));
        }

        return $this->options[$option];
    }

    public function handleRequest(Request $request)
    {
        $this->request = $request;

        // Use current route as default
        if (null === $this->options['route']) {
            $this->options['route'] = $request->get('_route');
        }

        // Set item count per page
        if ($itemCountPerPage = $request->get($this->options['item_count_per_page_param'], null, true)) {
            if (in_array($itemCountPerPage, $this->options['item_count_per_page_choices'])) {
                $this->setItemCountPerPage($itemCountPerPage);
            }
        }

        // Set current page number
        if ($currentPageNumber = $request->get($this->options['current_page_number_param'], null, true)) {
            $this->setCurrentPageNumber($currentPageNumber);
        }

        // Set order by & filter
        $this->setOrderByFromRequest($request, $this->options['order_by_param']);
        $this->setFilterFromRequest($request, $this->options['filter_param']);
    }

    protected function setOrderByFromRequest(Request $request, $parameter)
    {
        $orderBy = $request->get($parameter, null, true);
        if (!is_array($orderBy)) {
            return;
        }

        $this->setOrderBy(new OrderBy($orderBy));
    }

    protected function setFilterFromRequest(Request $request, $parameter)
    {
        $filter = $request->get($parameter, null, true);
        if (!is_array($filter)) {
            return;
        }

        $fields    = (isset($filter['f']) && is_array($filter['f'])) ? $filter['f'] : array();
        $values    = (isset($filter['v']) && is_array($filter['v'])) ? $filter['v'] : array();
        $operators = (isset($filter['o']) && is_array($filter['o'])) ? $filter['o'] : array();
        $logical   = (isset($filter['l']) && is_array($filter['l'])) ? $filter['l'] : array();

        $this->setFilter(new Filter($fields, $values, $operators, $logical), 'pager');
    }
}