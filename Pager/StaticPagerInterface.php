<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

interface StaticPagerInterface
{
    public function getItemCountPerPageChoices();

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
