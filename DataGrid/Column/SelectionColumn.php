<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

class SelectionColumn extends AbstractColumn
{
    protected $name;

    public function isFilterable()
    {
        return false;
    }

    public function isSortable()
    {
        return false;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return 'selection';
    }
}
