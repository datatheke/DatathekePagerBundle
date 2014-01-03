<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Pager;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\ArrayAdapter;

class PagerTest extends \PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $pager;

    public function setUp()
    {
        $this->adapter = new ArrayAdapter(array(array('aa'), array('bb'), array('cc'), array('dd')));
        $this->pager   = new Pager($this->adapter, 2);
    }

    public function testGetAdapter()
    {
        $this->assertEquals($this->adapter, $this->pager->getAdapter());
    }

    public function testSetCurrentPageNumber()
    {
        $pager = clone $this->pager;

        $this->assertEquals(2, $pager->getNextPageNumber());
        $pager->setCurrentPageNumber(2);
        $this->assertEquals(1, $pager->getPreviousPageNumber());
    }
}