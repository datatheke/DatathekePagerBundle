<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\AutocompleteHandler;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Symfony\Component\HttpFoundation\Request;

class AutocompleteHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new AutocompleteHandler();

        $pager = PagerHelper::createPager();
        $request = new Request(array(
            'term' => 'jean',
        ));

        $handler->handleRequest($pager, $request);

        $this->assertEquals(1, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getTotalItemCount());
    }
}
