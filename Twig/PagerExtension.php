<?php

namespace Datatheke\Bundle\PagerBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Datatheke\Bundle\PagerBundle\Pager\Configuration;
use Datatheke\Bundle\PagerBundle\Pager\WebPager;

class PagerExtension extends \Twig_Extension
{
    protected $environment;
    protected $urlGenerator;
    protected $config;

    public function __construct(UrlGeneratorInterface $urlGenerator, Configuration $config)
    {
        $this->urlGenerator = $urlGenerator;
        $this->config       = $config;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getName()
    {
        return 'PagerExtension';
    }

    public function getFunctions()
    {
        return array(
            'pager_path'          => new \Twig_Function_Method($this, 'pagerPath'),
            'pager_form_path'     => new \Twig_Function_Method($this, 'pagerFormPath'),
            'pager_order_path'    => new \Twig_Function_Method($this, 'pagerOrderPath'),
            'pager_per_page_Path' => new \Twig_Function_Method($this, 'pagerPerPagePath'),

            'pager_page_range'    => new \Twig_Function_Method($this, 'pagerPageRange'),
        );
    }

    /**
     * From Zend\Paginator\Paginator
     */
    public function pagerPageRange(WebPager $pager, $pageRange = null)
    {
        if (null == $pageRange) {
            $pageRange = $this->config->getPageRange();
        }

        $pageNumber = $pager->getCurrentPageNumber();
        $pageCount  = $pager->getPageCount();

        if ($pageRange > $pageCount) {
            $pageRange = $pageCount;
        }

        $delta = ceil($pageRange / 2 );

        if ($pageNumber - $delta > $pageCount - $pageRange) {
            $lowerBound = $pageCount - $pageRange + 1;
            $upperBound = $pageCount;
        } else {
            if ($pageNumber - $delta < 0) {
                $delta = $pageNumber;
            }

            $offset     = $pageNumber - $delta;
            $lowerBound = $offset + 1;
            $upperBound = $offset + $pageRange;
        }

        $pages = array();
        for ($pageNumber = $lowerBound; $pageNumber <= $upperBound; $pageNumber++) {
            $pages[$pageNumber] = $pageNumber;
        }

        return $pages;
    }

    public function pagerPath(WebPager $pager, $page = null, array $parameters = array(), $route = null, array $include = array())
    {
        $defaults = array(
            $pager->getOption('filter_param') => $this->serializeFilter($pager),
        );

        return $this->renderPath($pager, $page, array_merge($defaults, $parameters), $route);
    }

    public function pagerFormPath(WebPager $pager, $page = null, array $parameters = array(), $route = null)
    {
        return $this->renderPath($pager, $page, $parameters, $route);
    }

    public function pagerOrderPath(WebPager $pager, $field, $order = 'desc', $page = null, array $parameters = array(), $route = null)
    {
        $defaults = array(
            $pager->getOption('filter_param')   => $this->serializeFilter($pager),
            $pager->getOption('order_by_param') => array($field => $order),
        );

        return $this->renderPath($pager, $page, array_merge($defaults, $parameters), $route);
    }

    public function pagerPerPagePath(WebPager $pager, $itemCountPerPage, $page = null, array $parameters = array(), $route = null)
    {
        $defaults = array(
            $pager->getOption('item_count_per_page_param') => (int) $itemCountPerPage,
            $pager->getOption('filter_param')              => $this->serializeFilter($pager),
        );

        return $this->renderPath($pager, $page, array_merge($defaults, $parameters), $route);
    }

    protected function renderPath(WebPager $pager, $page = null, array $parameters = array(), $route = null)
    {
        if (null === $route) {
            $route = $pager->getOption('route');
        }

        if (null === $page) {
            $page = $pager->getCurrentPageNumber();
        }

        $defaults = array(
            $pager->getOption('item_count_per_page_param') => (int) $pager->getItemCountPerPage(),
            $pager->getOption('current_page_number_param') => (int) $page,
            $pager->getOption('order_by_param')            => $pager->getOrderBy(),
        );

        $globals = $pager->getOption('parameters');

        return $this->urlGenerator->generate($route, array_merge($defaults, $globals, $parameters));
    }

    protected function serializeFilter(WebPager $pager)
    {
        list($fields, $values, $operators, $logical) = $pager->getFilter()->toArray();
        return array('f' => $fields, 'v' => $values, 'o' => $operators, 'l' => $logical);
    }
}
