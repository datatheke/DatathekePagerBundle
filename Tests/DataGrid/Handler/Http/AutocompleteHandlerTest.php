<?php

namespace Datatheke\Bundle\PagerBundle\Tests\DataGrid\Handler\Http;

use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\AutocompleteHandler;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Symfony\Component\HttpFoundation\Request;

class AutocompleteHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $handler = new AutocompleteHandler();

        $datagrid = PagerHelper::createDatagrid();
        $request = new Request(array(
            'term' => 'jean',
        ));

        $response = $handler->handleRequest($datagrid, $request);
        $pager = $datagrid->getPager();

        $this->assertEquals('[{"firstname":"jean","lastname":"bon"},{"firstname":"jean","lastname":"bon"},{"firstname":"jean","lastname":"veux"},{"firstname":"marc","lastname":"jean"},{"firstname":"jean","lastname":"doublon"}]', $response->getContent());
        $this->assertEquals(1, $pager->getCurrentPageNumber());
        $this->assertEquals(5, $pager->getTotalItemCount());
    }

    public function testJsonPHandleRequest()
    {
        $handler = new AutocompleteHandler();
        $handler->setJsonPPadding('callback');

        $datagrid = PagerHelper::createDatagrid();
        $request = new Request(array(
            'callback' => 'cb_test',
            'term' => 'marc',
        ));

        $response = $handler->handleRequest($datagrid, $request);

        $this->assertContains('cb_test([{"firstname":"marc","lastname":"jean"}]);', $response->getContent());
    }
}
