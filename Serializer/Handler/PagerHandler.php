<?php

namespace Datatheke\Bundle\PagerBundle\Serializer\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
// use JMS\Serializer\XmlSerializationVisitor;
// use JMS\Serializer\YamlSerializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\GenericSerializationVisitor;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Context;

use Datatheke\Bundle\PagerBundle\Pager\Pager;

class PagerHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        $methods = array();
        foreach (array('json') as $format) { // TODO: xml, yml
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\Pager\Pager',
                'format'    => $format,
            );
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\Pager\HttpPager',
                'format'    => $format,
            );
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\Pager\ConsolePager',
                'format'    => $format,
            );
        }

        return $methods;
    }

    public function serializePagerToJson(JsonSerializationVisitor $visitor, Pager $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    public function serializeHttpPagerToJson(JsonSerializationVisitor $visitor, Pager $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    public function serializeConsolePagerToJson(JsonSerializationVisitor $visitor, Pager $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    private function convertPagerToArray(GenericSerializationVisitor $visitor, Pager $pager, array $type, Context $context)
    {
        $isRoot = null === $visitor->getRoot();

        $result = array(
            'current_page_number' => $pager->getCurrentPageNumber(),
            'page_count'          => $pager->getPageCount(),

            'item_count_per_page' => $pager->getItemCountPerPage(),
            'total_item_count'    => $pager->getTotalItemCount(),

            'first_item_number'   => $pager->getFirstItemNumber(),
            'last_item_number'    => $pager->getLastItemNumber(),
            'current_item_count'  => $pager->getCurrentItemCount(),

            'items'               => $visitor->getNavigator()->accept($pager->getItems(), array('name' => 'array'), $context)
            );

        if ($isRoot) {
            $visitor->setRoot($result);
        }

        return $result;
    }
}
