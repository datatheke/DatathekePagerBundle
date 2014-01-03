<?php

namespace Datatheke\Bundle\PagerBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Datatheke\Bundle\PagerBundle\Pager\Configuration;
use Datatheke\Bundle\PagerBundle\Pager\PagerViewInterface;

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
        return 'DatathekePagerExtension';
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
    public function pagerPageRange(PagerViewInterface $pager, $pageRange = null)
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

    public function pagerPath(PagerViewInterface $pager, $page = null, array $parameters = array(), $route = null, array $include = array())
    {
        $defaults = array(
            $pager->getFilterParam() => $this->serializeFilter($pager),
        );

        return $this->renderPath($pager, $page, array_merge($defaults, $parameters), $route);
    }

    public function pagerFormPath(PagerViewInterface $pager, $page = null, array $parameters = array(), $route = null)
    {
        return $this->renderPath($pager, $page, $parameters, $route);
    }

    public function pagerOrderPath(PagerViewInterface $pager, $field, $order = 'desc', $page = null, array $parameters = array(), $route = null)
    {
        $defaults = array(
            $pager->getFilterParam()  => $this->serializeFilter($pager),
            $pager->getOrderByParam() => array($field => $order),
        );

        return $this->renderPath($pager, $page, array_merge($defaults, $parameters), $route);
    }

    public function pagerPerPagePath(PagerViewInterface $pager, $itemCountPerPage, $page = null, array $parameters = array(), $route = null)
    {
        $defaults = array(
            $pager->getItemCountPerPageParam() => (int) $itemCountPerPage,
            $pager->getFilterParam()           => $this->serializeFilter($pager),
        );

        return $this->renderPath($pager, $page, array_merge($defaults, $parameters), $route);
    }

    protected function renderPath(PagerViewInterface $pager, $page = null, array $parameters = array(), $route = null)
    {
        if (null === $route) {
            $route = $pager->getRoute();
        }

        if (null === $page) {
            $page = $pager->getCurrentPageNumber();
        }

        $defaults = array(
            $pager->getItemCountPerPageParam()  => (int) $pager->getItemCountPerPage(),
            $pager->getCurrentPageNumberParam() => (int) $page,
            $pager->getOrderByParam()           => $pager->getOrderBy(),
        );

        $globals = $pager->getParameters();

        return $this->urlGenerator->generate($route, array_merge($defaults, $globals, $parameters));
    }

    protected function serializeFilter(PagerViewInterface $pager)
    {
        list($fields, $values, $operators, $logical) = $pager->getFilter()->toArray();
        return array('f' => $fields, 'v' => $values, 'o' => $operators, 'l' => $logical);
    }
}
