<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Pagerfanta\Adapter\AdapterInterface as PagerFantaAdapterInterface;

class PagerFantaAdapter implements AdapterInterface
{
    protected $adapter;
    protected $fields;

    public function __construct(PagerFantaAdapterInterface $adapter, array $fields = array())
    {
        $this->adapter = $adapter;
        $this->fields = $fields;
    }

    public function getItems($offset = 0, $itemCountPerPage = null)
    {
        return $this->adapter->getSlice($offset, $itemCountPerPage);
    }

    public function count()
    {
        return $this->adapter->getNbResults();
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        // Not implemented

        return $this;
    }

    public function setFilter(Filter $filter = null, $group = 'default')
    {
        // Not implemented

        return $this;
    }
}
