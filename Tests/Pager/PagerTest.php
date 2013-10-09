<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Pager;

class PagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAdapter()
    {
        $adapter = $this->getMock('Datatheke\\Bundle\\PagerBundle\\Pager\\Adapter\\AdapterInterface');
        $pager   = new Pager($adapter, 10);

        $this->assertEquals($adapter, $pager->getAdapter());

        $adapter2 = clone $adapter;
        $this->assertNotEquals($adapter2, $pager->getAdapter());
    }
}