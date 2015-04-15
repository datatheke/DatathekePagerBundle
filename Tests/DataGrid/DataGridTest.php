<?php

namespace Datatheke\Bundle\PagerBundle\Tests\DataGrid;

use Symfony\Component\HttpFoundation\Request;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\StaticColumn;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Action\Action;

class DataGridTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = PagerHelper::createDataGridFactory();
    }

    public function testCustomizedHttpDataGrid()
    {
        $datagrid = $this->factory->createHttpDataGrid(PagerHelper::getPersons());
        $request  = new Request();

        $actions = new StaticColumn('Actions');
        $actions->addAction(new Action('View', 'item_view', array(
            'icon' => '.glyphicon-eye-open',
            )
        ));
        $actions->addAction(new Action('Delete', 'item_delete', array(
            'item_mapping' => array('id' => 'lastname'),
            )
        ));

        $columns = $datagrid->getColumns();
        $this->assertCount(2, $columns);
        $this->assertEquals('firstname', key($columns));

        $datagrid->addColumn($actions, 'action');
        $datagrid->sortColumns(array('action', 'firstname', 'lastname'));
        $columns = $datagrid->getColumns();
        $this->assertCount(3, $columns);
        $this->assertEquals('action', key($columns));

        $datagrid->getColumn('firstname')->hide();
        $columns = $datagrid->getColumns();
        $this->assertCount(2, $columns);

        $view = $datagrid->handleRequest($request);
        $this->assertInstanceOf('Datatheke\Bundle\PagerBundle\DataGrid\DataGridViewInterface', $view);
    }
}
