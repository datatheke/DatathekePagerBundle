<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;

class Pager
{
	protected $adapter;
    protected $paginator;

    protected $orderBy;
    protected $filter;

	public function __construct(AdapterInterface $adapter, $itemCountPerPage, $currentPageNumber = 1)
	{
		$this->adapter   = $adapter;
        $this->paginator = new Paginator($itemCountPerPage, $currentPageNumber);

        $this->orderBy   = new OrderBy();
        $this->filter    = new Filter();
	}

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setCurrentPageNumber($currentPageNumber)
    {
        $this->paginator->setCurrentPageNumber($currentPageNumber);

        return $this;
    }

    public function getCurrentPageNumber()
    {
        return $this->getPaginator()->getCurrentPageNumber();
    }

    public function setItemCountPerPage($itemCountPerPage)
    {
        $this->paginator->setItemCountPerPage($itemCountPerPage);

        return $this;
    }

    public function getItemCountPerPage()
    {
        return $this->getPaginator()->getItemCountPerPage();
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        $this->orderBy = $orderBy;
        $this->adapter->setOrderBy($orderBy);

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setFilter(Filter $filter = null)
    {
        $this->filter = $filter;
        $this->adapter->setFilter($filter);

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getItems()
    {
        return $this->adapter->getItems($this->getPaginator()->getItemOffset(), $this->getPaginator()->getItemCountPerPage());
    }

    public function getPageCount()
    {
        return $this->getPaginator()->getPageCount();
    }

    public function getTotalItemCount()
    {
        return $this->getPaginator()->getTotalItemCount();
    }

    public function getFirstItemNumber()
    {
        return $this->getPaginator()->getFirstItemNumber();
    }

    public function getLastItemNumber()
    {
        return $this->getPaginator()->getLastItemNumber();
    }

    public function getPreviousPageNumber()
    {
        return $this->getPaginator()->getPreviousPageNumber();
    }

    public function getNextPageNumber()
    {
        return $this->getPaginator()->getNextPageNumber();
    }

    protected function getPaginator()
    {
        // Count items only when we really need the paginator
        $this->paginator->setTotalItemCount($this->adapter->count());

        return $this->paginator;
    }
}
