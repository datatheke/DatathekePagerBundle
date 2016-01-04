<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ViewHandler implements HttpHandlerInterface
{
    const FILTER_MODE_SIMPLIFIED = 1;
    const FILTER_MODE_ADVANCED = 2;

    protected $options;

    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        static $pagerNum;
        $pagerParam = '_p'.$pagerNum++;

        $resolver->setDefaults(array(
            'pager_param' => $pagerParam,
            'order_by_param' => function (Options $options) { return $options['pager_param'].'[o]'; },
            'filter_param' => function (Options $options) { return $options['pager_param'].'[f]'; },
            'current_page_number_param' => function (Options $options) { return $options['pager_param'].'[p]'; },
            'item_count_per_page_param' => function (Options $options) { return $options['pager_param'].'[pp]'; },
            'filter_mode' => self::FILTER_MODE_ADVANCED,

            // Deprecated, must be set in PagerView
            'route' => null,
            'parameters' => array(),
            )
        );
    }

    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    public function getOption($option)
    {
        if (!$this->hasOption($option)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $option));
        }

        return $this->options[$option];
    }

    public function handleRequest(PagerInterface $pager, Request $request)
    {
        // Use current route as default
        if (null === $this->options['route']) {
            $this->options['route'] = $request->attributes->get('_route');
        }

        // Set item count per page
        if ($itemCountPerPage = $this->glob($request, $this->options['item_count_per_page_param'])) {
            $pager->setItemCountPerPage($itemCountPerPage);
        }

        // Set current page number
        if ($currentPageNumber = $this->glob($request, $this->options['current_page_number_param'])) {
            $pager->setCurrentPageNumber($currentPageNumber);
        }

        // Set order by & filter
        $this->setOrderByFromRequest($pager, $request, $this->options['order_by_param']);
        $this->setFilterFromRequest($pager, $request, $this->options['filter_param']);

        return new PagerView($pager, $this);
    }

    protected function setOrderByFromRequest(PagerInterface $pager, Request $request, $parameter)
    {
        $orderBy = $this->glob($request, $parameter);
        if (!is_array($orderBy)) {
            return;
        }

        $pager->setOrderBy(new OrderBy($orderBy));
    }

    protected function setFilterFromRequest(PagerInterface $pager, Request $request, $parameter)
    {
        $filter = $this->glob($request, $parameter);
        if (!is_array($filter)) {
            return;
        }
        if (self::FILTER_MODE_SIMPLIFIED === $this->getOption('filter_mode')) {
            $pager->setFilter(new Filter(array_keys($filter), array_values($filter)), 'handler');
        } else {
            $fields = (isset($filter['f']) && is_array($filter['f'])) ? $filter['f'] : array();
            $values = (isset($filter['v']) && is_array($filter['v'])) ? $filter['v'] : array();
            $operators = (isset($filter['o']) && is_array($filter['o'])) ? $filter['o'] : array();
            $logical = (isset($filter['l']) && is_array($filter['l'])) ? $filter['l'] : array();

            $pager->setFilter(new Filter($fields, $values, $operators, $logical), 'handler');
        }
    }

    protected function glob(Request $request, $param, $default = null)
    {
        $deep = false !== $pos = strpos($param, '[');

        if (false === $deep) {
            return $request->get($param, $default);
        }

        $root = substr($param, 0, $pos);

        if (null !== $value = $this->getDeep($request->query->get($root), $param)) {
            return $value;
        }

        if (null !== $value = $this->getDeep($request->request->get($root), $param)) {
            return $value;
        }

        return $default;
    }

    private function getDeep($value, $key)
    {
        $pos = strpos($key, '[');

        $currentKey = null;
        for ($i = $pos, $c = strlen($key); $i < $c; ++$i) {
            $char = $key[$i];
            if ('[' === $char) {
                if (null !== $currentKey) {
                    throw new \InvalidArgumentException(sprintf('Malformed path. Unexpected "[" at position %d.', $i));
                }
                $currentKey = '';
            } elseif (']' === $char) {
                if (null === $currentKey) {
                    throw new \InvalidArgumentException(sprintf('Malformed path. Unexpected "]" at position %d.', $i));
                }
                if (!is_array($value) || !array_key_exists($currentKey, $value)) {
                    return;
                }
                $value = $value[$currentKey];
                $currentKey = null;
            } else {
                if (null === $currentKey) {
                    throw new \InvalidArgumentException(sprintf('Malformed path. Unexpected "%s" at position %d.', $char, $i));
                }
                $currentKey .= $char;
            }
        }

        if (null !== $currentKey) {
            throw new \InvalidArgumentException(sprintf('Malformed path. Path must end with "]".'));
        }

        return $value;
    }
}
