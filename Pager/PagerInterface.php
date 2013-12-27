<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;

interface PagerInterface
{
    public function getAdapter();

    public function setCurrentPageNumber($currentPageNumber);

    public function getCurrentPageNumber();

    public function setItemCountPerPage($itemCountPerPage);

    public function getItemCountPerPage();

    public function setOrderBy(OrderBy $orderBy = null);

    public function getOrderBy();

    public function setFilter(Filter $filter = null);

    public function getFilter();

    public function getItems();

    public function getPageCount();

    public function getTotalItemCount();

    public function getFirstItemNumber();

    public function getLastItemNumber();

    public function getPreviousPageNumber();

    public function getNextPageNumber();

    public function getCurrentItemCount();
}