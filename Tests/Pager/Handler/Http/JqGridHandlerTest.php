<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\JqGridHandler;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Symfony\Component\HttpFoundation\Request;

class JqGridHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new JqGridHandler();

        $pager = PagerHelper::createPager();
        $request = new Request(array(
            'page' => 3,
            'rows' => 5,
        ));

        $handler->handleRequest($pager, $request);

        $this->assertEquals(3, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getItemCountPerPage());
    }
}
