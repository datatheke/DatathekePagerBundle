<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\ArrayAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    static protected $source = array(
        array('firstname' => 'john', 'lastname' => 'doe', 'age' => 32),
        array('firstname' => 'jean', 'lastname' => 'bon', 'age' => 25)
    );

    public function testGuessFields()
    {
        $adapter = new ArrayAdapter(self::$source);
        $fields  = $adapter->getFields();

        $this->assertEquals('firstname', $fields['firstname']->getQualifier());
        $this->assertEquals(Field::TYPE_NUMBER, $fields['age']->getType());
    }

    public function testOrderBy()
    {
        $adapter = new ArrayAdapter(self::$source);

        $adapter->setOrderBy(new OrderBy(array('age' => OrderBy::ASC)));
        $items = $adapter->getItems();
        $this->assertEquals('bon', $items[0]['lastname']);
        $this->assertEquals('doe', $items[1]['lastname']);

        $adapter->setOrderBy(new OrderBy(array('age' => OrderBy::DESC)));
        $items = $adapter->getItems();
        $this->assertEquals('bon', $items[1]['lastname']);
        $this->assertEquals('doe', $items[0]['lastname']);
    }

    public function testFilter()
    {
        $adapter = new ArrayAdapter(self::$source);

        $this->assertEquals(2, $adapter->count());
        $adapter->setFilter(new Filter(array('lastname'), array('bon'), array(Filter::OPERATOR_EQUALS)));
        $this->assertEquals(1, $adapter->count());

        $items   = $adapter->getItems();
        $this->assertEquals(25, $items[0]['age']);
    }
}