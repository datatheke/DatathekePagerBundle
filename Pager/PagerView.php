<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler;

class PagerView implements PagerViewInterface
{
    protected $pager;
    protected $handler;

    public function __construct(PagerInterface $pager, ViewHandler $handler)
    {
        $this->pager   = $pager;
        $this->handler = $handler;
    }

    public function getFilterParam()
    {
        return $this->handler->getOption('filter_param');
    }

    public function getOrderByParam()
    {
        return $this->handler->getOption('order_by_param');
    }

    public function getCurrentPageNumberParam()
    {
        return $this->handler->getOption('current_page_number_param');
    }

    public function getItemCountPerPageParam()
    {
        return $this->handler->getOption('item_count_per_page_param');
    }

    public function getRoute()
    {
        return $this->handler->getOption('route');
    }

    public function getParameters()
    {
        return $this->handler->getOption('parameters');
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
