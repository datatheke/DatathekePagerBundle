<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Symfony\Component\HttpFoundation\Request;

class ViewHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateHandler()
    {
        $handler = new ViewHandler(array('pager_param' => 'test'));

        $this->assertEquals('test', $handler->getOption('pager_param'));
    }

    public function testHandleRequest()
    {
        $handler = new ViewHandler(array('pager_param' => '_p'));

        $pager = PagerHelper::createPager();
        $request = new Request(array('_p' => array(
            'p' => 3,
            'pp' => 5,
            ),
        ));

        $view = $handler->handleRequest($pager, $request);

        $this->assertEquals(3, $view->getCurrentPageNumber());
        $this->assertEquals(5, $view->getItemCountPerPage());
        $this->assertInstanceOf('Datatheke\Bundle\PagerBundle\Pager\PagerViewInterface', $view);
    }
}
