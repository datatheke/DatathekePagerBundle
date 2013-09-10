<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

class StaticColumn extends AbstractColumn
{
    public function __construct($label = null, $order = 0)
    {
        parent::__construct(null, $label, $order);
    }

    public function isFilterable()
    {
        return false;
    }

    public function isSortable()
    {
        return false;
    }

    public function getType()
    {
        return 'static';
    }
}
