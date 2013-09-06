<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOffset()
    {
        $paginator = new Paginator(256, 25, 5);

        $this->assertEquals(101, $paginator->getOffset());
    }
}