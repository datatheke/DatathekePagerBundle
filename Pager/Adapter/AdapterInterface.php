<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use \Countable;

use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;

interface AdapterInterface extends Countable
{
    public function getItems($offset = 0, $itemCountPerPage = null);

    public function getFields();

    public function setOrderBy(OrderBy $orderBy = null);

    public function setFilter(Filter $filter = null);
}