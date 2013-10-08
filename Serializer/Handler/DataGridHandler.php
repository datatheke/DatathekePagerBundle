<?php

namespace Datatheke\Bundle\PagerBundle\Serializer\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
// use JMS\Serializer\XmlSerializationVisitor;
// use JMS\Serializer\YamlSerializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\GenericSerializationVisitor;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Context;

use Datatheke\Bundle\PagerBundle\DataGrid\DataGrid;

class DataGridHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        $methods = array();
        foreach (array('json') as $format) { // TODO: xml, yml
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\DataGrid\DataGrid',
                'format'    => $format,
            );
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\DataGrid\WebDataGrid',
                'format'    => $format,
            );
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\DataGrid\ConsoleDataGrid',
                'format'    => $format,
            );
        }

        return $methods;
    }

    public function serializeDataGridToJson(JsonSerializationVisitor $visitor, DataGrid $datagrid, array $type, Context $context)
    {
        return $this->convertDataGridToArray($visitor, $datagrid, $type, $context);
    }

    public function serializeWebDataGridToJson(JsonSerializationVisitor $visitor, DataGrid $datagrid, array $type, Context $context)
    {
        return $this->convertDataGridToArray($visitor, $datagrid, $type, $context);
    }

    public function serializeConsoleDataGridToJson(JsonSerializationVisitor $visitor, DataGrid $datagrid, array $type, Context $context)
    {
        return $this->convertDataGridToArray($visitor, $datagrid, $type, $context);
    }

    private function convertDataGridToArray(GenericSerializationVisitor $visitor, DataGrid $datagrid, array $type, Context $context)
    {
        $pager = $datagrid->getPager();

        $isRoot = null === $visitor->getRoot();

        $result = array(
            'current_page_number' => $pager->getCurrentPageNumber(),
            'page_count'          => $pager->getPageCount(),

            'item_count_per_page' => $pager->getItemCountPerPage(),
            'total_item_count'    => $pager->getTotalItemCount(),

            'first_item_number'   => $pager->getFirstItemNumber(),
            'last_item_number'    => $pager->getLastItemNumber(),
            'current_item_count'  => $pager->getCurrentItemCount(),

            // TODO: format items
            'items'               => $visitor->getNavigator()->accept($pager->getItems(), array('name' => 'array'), $context),

            // TODO: add columns
            'columns'             => array()
            );

        if ($isRoot) {
            $visitor->setRoot($result);
        }

        return $result;
    }
}