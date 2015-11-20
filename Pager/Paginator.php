<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

class Paginator
{
    protected $pageCount;
    protected $currentPageNumber;
    protected $previousPageNumber;
    protected $nextPageNumber;

    protected $itemCountPerPage;
    protected $totalItemCount;
    protected $itemOffset;
    protected $currentItemCount;
    protected $firstItemNumber;
    protected $lastItemNumber;

    protected $initialized;

    public function __construct($itemCountPerPage, $currentPageNumber = 1, $totalItemCount = 0)
    {
        $this->setItemCountPerPage($itemCountPerPage);
        $this->setCurrentPageNumber($currentPageNumber);
        $this->setTotalItemCount($totalItemCount);

        $this->initialized = false;
    }

    public function setTotalItemCount($totalItemCount)
    {
        if ($totalItemCount !== $this->totalItemCount) {
            $this->totalItemCount = (int) $totalItemCount;
            $this->initialized = false;
        }

        return $this;
    }

    public function setItemCountPerPage($itemCountPerPage)
    {
        if ($itemCountPerPage !== $this->itemCountPerPage) {
            $this->itemCountPerPage = (int) $itemCountPerPage;
            $this->initialized = false;
        }

        return $this;
    }

    public function setCurrentPageNumber($currentPageNumber)
    {
        if ($currentPageNumber !== $this->currentPageNumber) {
            $this->currentPageNumber = (int) $currentPageNumber;
            $this->initialized = false;
        }

        return $this;
    }

    protected function initialize()
    {
        if ($this->itemCountPerPage < 1) {
            $this->itemCountPerPage = 1;
        }

        $this->pageCount = ceil($this->totalItemCount / $this->itemCountPerPage);
        if ($this->pageCount < 1) {
            $this->pageCount = 1;
        }

        if ($this->currentPageNumber < 1) {
            $this->currentPageNumber = 1;
        } elseif ($this->currentPageNumber > $this->pageCount) {
            $this->currentPageNumber = $this->pageCount;
        }

        if ($this->currentPageNumber < $this->pageCount) {
            $this->currentItemCount = $this->itemCountPerPage;
        } else {
            $this->currentItemCount = $this->totalItemCount - (($this->currentPageNumber - 1) * $this->itemCountPerPage);
        }

        $this->itemOffset = (($this->currentPageNumber - 1) * $this->itemCountPerPage);
        $this->firstItemNumber = (($this->currentPageNumber - 1) * $this->itemCountPerPage) + 1;
        $this->lastItemNumber = $this->firstItemNumber + $this->currentItemCount - 1;

        if (!$this->totalItemCount) {
            $this->firstItemNumber = 0;
        }

        if ($this->currentPageNumber - 1 > 0) {
            $this->previousPageNumber = $this->currentPageNumber - 1;
        }

        if ($this->currentPageNumber + 1 <= $this->pageCount) {
            $this->nextPageNumber = $this->currentPageNumber + 1;
        }

        $this->initialized = true;
    }

    public function getItemCountPerPage()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->itemCountPerPage;
    }

    public function getPageCount()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->pageCount;
    }

    public function getCurrentPageNumber()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->currentPageNumber;
    }

    public function getItemOffset()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->itemOffset;
    }

    public function getFirstItemNumber()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->firstItemNumber;
    }

    public function getLastItemNumber()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->lastItemNumber;
    }

    public function getTotalItemCount()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->totalItemCount;
    }

    public function getPreviousPageNumber()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->previousPageNumber;
    }

    public function getNextPageNumber()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->nextPageNumber;
    }

    public function getCurrentItemCount()
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        return $this->currentItemCount;
    }
}
