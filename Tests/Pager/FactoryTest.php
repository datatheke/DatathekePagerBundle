<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = PagerHelper::createPagerFactory();
    }

    public function testCreateHttpPager()
    {
        $pager = $this->factory->createHttpPager(array(array('aa'), array('bb'), array('cc')));

        $this->assertEquals(3, $pager->getTotalItemCount());
        $this->assertEquals(26, $pager->getItemCountPerPage());
        $this->assertInstanceOf('Datatheke\Bundle\PagerBundle\Pager\HttpPager', $pager);
    }

    public function testCreateConsolePager()
    {
        $pager = $this->factory->createConsolePager(array(array('aa'), array('bb'), array('cc')));

        $this->assertEquals(3, $pager->getTotalItemCount());
        $this->assertEquals(26, $pager->getItemCountPerPage());
        $this->assertInstanceOf('Datatheke\Bundle\PagerBundle\Pager\ConsolePager', $pager);
    }
}
