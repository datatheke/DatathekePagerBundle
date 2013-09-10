<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Pager;
use Datatheke\Bundle\PagerBundle\Pager\Configuration;

class PagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAdapter()
    {
        $config  = new Configuration(10, 1);
        $adapter = $this->getMock('Datatheke\\Bundle\\PagerBundle\\Pager\\Adapter\\AdapterInterface');
        $pager   = new Pager($adapter, $config);

        $this->assertEquals($adapter, $pager->getAdapter());

        $adapter2 = clone $adapter;
        $this->assertNotEquals($adapter2, $pager->getAdapter());
    }
}