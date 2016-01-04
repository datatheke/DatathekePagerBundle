<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\DynatableHandler;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Symfony\Component\HttpFoundation\Request;

class DynatableHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new DynatableHandler();

        $pager = PagerHelper::createPager();
        $request = new Request(array(
            'page' => 3,
            'perPage' => 5,
        ));

        $handler->handleRequest($pager, $request);

        $this->assertEquals(3, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getItemCountPerPage());
    }
}
