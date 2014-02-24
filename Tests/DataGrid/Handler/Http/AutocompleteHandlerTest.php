<?php

namespace Datatheke\Bundle\PagerBundle\Tests\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\AutocompleteHandler;

class AutocompleteHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new AutocompleteHandler();

        $datagrid = PagerHelper::createDatagrid();
        $request = new Request(array(
            'term' => 'jean'
        ));

        $handler->handleRequest($datagrid, $request);
        $pager = $datagrid->getPager();

        $this->assertEquals(1, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getTotalItemCount());
    }
}
