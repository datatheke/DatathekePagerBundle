<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\FlexigridHandler;

class FlexigridHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new FlexigridHandler();

        $pager   = PagerHelper::createPager();
        $request = new Request(array(), array(
            'page' => 3,
            'rp'   => 5,
        ));

        $handler->handleRequest($pager, $request);

        $this->assertEquals(3, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getItemCountPerPage());
    }
}
