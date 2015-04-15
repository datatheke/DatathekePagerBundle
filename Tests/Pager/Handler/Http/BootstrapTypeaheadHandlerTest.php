<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\BootstrapTypeaheadHandler;

class BootstrapTypeaheadHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new BootstrapTypeaheadHandler();

        $pager   = PagerHelper::createPager();
        $request = new Request(array(
            'query' => 'jean',
        ));

        $handler->handleRequest($pager, $request);

        $this->assertEquals(1, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getTotalItemCount());
    }
}
