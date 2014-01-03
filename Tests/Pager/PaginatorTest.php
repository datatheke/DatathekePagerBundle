<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    protected $paginator1;
    protected $paginator2;

    public function setUp()
    {
        $this->paginator1 = new Paginator(10, 1, 4567);
        $this->paginator2 = new Paginator(25, 5, 256);
    }

    public function testSetTotalItemCount()
    {
        $paginator = clone $this->paginator1;

        $this->assertEquals(457, $paginator->getPageCount());
        $paginator->setTotalItemCount(852);
        $this->assertEquals(86, $paginator->getPageCount());
    }

    public function testSetItemCountPerPage()
    {
        $paginator = clone $this->paginator2;

        $this->assertEquals(125, $paginator->getLastItemNumber());
        $paginator->setItemCountPerPage(36);
        $this->assertEquals(180, $paginator->getLastItemNumber());
    }

    public function testGetOffset()
    {
        $this->assertEquals(1, $this->paginator1->getFirstItemNumber());
        $this->assertEquals(101, $this->paginator2->getFirstItemNumber());
    }
}