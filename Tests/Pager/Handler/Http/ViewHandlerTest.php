<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler;

class ViewHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateHandler()
    {
        $handler = new ViewHandler(array('pager_param' => 'test'));

        $this->assertEquals('test', $handler->getOption('pager_param'));
    }
}