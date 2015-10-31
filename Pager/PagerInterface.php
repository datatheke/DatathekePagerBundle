<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

interface PagerInterface extends StaticPagerInterface
{
    public function getFields();

    public function setCurrentPageNumber($currentPageNumber);

    public function setItemCountPerPage($itemCountPerPage);

    public function setOrderBy(OrderBy $orderBy = null);

    public function setFilter(Filter $filter = null, $name = 'default');
}
