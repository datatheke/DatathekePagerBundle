<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectConstructor()
    {
        $config = new Configuration(10, array(5, 10), 5);

        $this->assertEquals(10, $config->getItemCountPerPage());
        $this->assertEquals(array(5, 10), $config->getItemCountPerPageChoices());
        $this->assertEquals(5, $config->getPageRange());
    }

    public function testUncorrectConstructor()
    {
        $config = new Configuration('foo', array(), 'bar');

        $this->assertNotEquals('foo', $config->getItemCountPerPage());
        $this->assertNotEquals(array(10), $config->getItemCountPerPageChoices());
        $this->assertNotEquals('bar', $config->getPageRange());
    }
}