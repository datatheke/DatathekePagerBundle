<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\PagerFantaAdapter;
use Pagerfanta\Adapter\ArrayAdapter;

class PagerFantaAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected static $source = array(
        array('firstname' => 'john', 'lastname' => 'doe', 'age' => 32, 'friend' => true),
        array('firstname' => 'jean', 'lastname' => 'bon', 'age' => 25, 'friend' => false),
    );

    public function testCountAndSlice()
    {
        $pagerFantaArrayAdapter = new ArrayAdapter(self::$source);

        $adapter = new PagerFantaAdapter($pagerFantaArrayAdapter);
        $this->assertCount(2, $adapter);

        $items = $adapter->getItems(0, 1);
        $this->assertEquals('doe', $items[0]['lastname']);
    }
}
