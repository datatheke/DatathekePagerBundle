<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler;

class PagerView implements PagerViewInterface
{
    protected $pager;
    protected $handler;

    protected $filterParam;
    protected $orderByParam;
    protected $currentPageNumberParam;
    protected $itemCountPerPage;
    protected $route;
    protected $parameters;

    public function __construct(PagerInterface $pager, ViewHandler $handler = null)
    {
        $this->pager = $pager;
        $this->handler = $handler;

        $this->filterParam = $this->handler->getOption('filter_param');
        $this->orderByParam = $this->handler->getOption('order_by_param');
        $this->currentPageNumberParam = $this->handler->getOption('current_page_number_param');
        $this->itemCountPerPage = $this->handler->getOption('item_count_per_page_param');
        $this->route = $this->handler->getOption('route');
        $this->parameters = $this->handler->getOption('parameters');
    }

    public function getFilterParam()
    {
        return $this->filterParam;
    }

    public function getOrderByParam()
    {
        return $this->orderByParam;
    }

    public function getCurrentPageNumberParam()
    {
        return $this->currentPageNumberParam;
    }

    public function getItemCountPerPageParam()
    {
        return $this->itemCountPerPage;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getItemCountPerPageChoices()
    {
        return $this->pager->getItemCountPerPageChoices();
    }

    public function getCurrentPageNumber()
    {
        return $this->pager->getCurrentPageNumber();
    }

    public function getItemCountPerPage()
    {
        return $this->pager->getItemCountPerPage();
    }

    public function getOrderBy()
    {
        return $this->pager->getOrderBy();
    }

    public function getFilter()
    {
        return $this->pager->getFilter();
    }

    public function getItems()
    {
        return $this->pager->getItems();
    }

    public function getPageCount()
    {
        return $this->pager->getPageCount();
    }

    public function getTotalItemCount()
    {
        return $this->pager->getTotalItemCount();
    }

    public function getFirstItemNumber()
    {
        return $this->pager->getFirstItemNumber();
    }

    public function getLastItemNumber()
    {
        return $this->pager->getLastItemNumber();
    }

    public function getPreviousPageNumber()
    {
        return $this->pager->getPreviousPageNumber();
    }

    public function getNextPageNumber()
    {
        return $this->pager->getNextPageNumber();
    }

    public function getCurrentItemCount()
    {
        return $this->pager->getCurrentItemCount();
    }
}
