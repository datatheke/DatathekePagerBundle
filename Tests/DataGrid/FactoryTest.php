<?php

namespace Datatheke\Bundle\PagerBundle\Tests\DataGrid;

use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = PagerHelper::createDataGridFactory();
    }

    public function testCreateHttpDataGrid()
    {
        $datagrid = $this->factory->createHttpDataGrid(array(array('aa'), array('bb'), array('cc')));

        $this->assertCount(1, $datagrid->getColumns());
        $this->assertInstanceOf('Datatheke\Bundle\PagerBundle\DataGrid\HttpDataGrid', $datagrid);
    }

    public function testCreateConsoleDataGrid()
    {
        $datagrid = $this->factory->createConsoleDataGrid(array(array('aa'), array('bb'), array('cc')));

        $this->assertCount(1, $datagrid->getColumns());
        $this->assertInstanceOf('Datatheke\Bundle\PagerBundle\DataGrid\ConsoleDataGrid', $datagrid);
    }
}
