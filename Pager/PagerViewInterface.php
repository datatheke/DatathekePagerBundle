<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

interface PagerViewInterface
{
    public function getFilterParam();

    public function getOrderByParam();

    public function getCurrentPageNumberParam();

    public function getItemCountPerPageParam();

    public function getItemCountPerPageChoices();

    public function getRoute();

    public function getParameters();

    public function getCurrentPageNumber();

    public function getItemCountPerPage();

    public function getOrderBy();

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
