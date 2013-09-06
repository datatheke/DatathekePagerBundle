<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectConstructor()
    {
        $config = new Configuration(10, 20);

        $this->assertEquals(10, $config->getItemCountPerPage());
        $this->assertEquals(20, $config->getCurrentPageNumber());
    }

    public function testUncorrectConstructor()
    {
        $config = new Configuration('foo', 'bar');

        $this->assertNotEquals('foo', $config->getItemCountPerPage());
        $this->assertNotEquals('bar', $config->getCurrentPageNumber());
    }
}