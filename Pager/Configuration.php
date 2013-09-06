<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

class Configuration
{
    protected $itemCountPerPage;
    protected $itemCountPerPageChoices;
    protected $pageRange;

    public function __construct($itemCountPerPage, array $itemCountPerPageChoices, $pageRange)
    {
        $this->itemCountPerPage        = (int) $itemCountPerPage;
        $this->itemCountPerPageChoices = (array) $itemCountPerPageChoices;
        $this->pageRange               = (int) $pageRange;
    }

    public function getItemCountPerPage()
    {
        return $this->itemCountPerPage;
    }

    public function getItemCountPerPageChoices()
    {
        return $this->itemCountPerPageChoices;
    }

    public function getPageRange()
    {
        return $this->pageRange;
    }
}
