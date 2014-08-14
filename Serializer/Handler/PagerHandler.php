<?php

namespace Datatheke\Bundle\PagerBundle\Serializer\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
// use JMS\Serializer\XmlSerializationVisitor;
// use JMS\Serializer\YamlSerializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\GenericSerializationVisitor;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Context;

use Datatheke\Bundle\PagerBundle\Pager\StaticPagerInterface;

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
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type'      => 'Datatheke\Bundle\PagerBundle\Pager\PagerView',
                'format'    => $format,
            );
        }

        return $methods;
    }

    public function serializePagerToJson(JsonSerializationVisitor $visitor, StaticPagerInterface $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    public function serializeHttpPagerToJson(JsonSerializationVisitor $visitor, StaticPagerInterface $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    public function serializeConsolePagerToJson(JsonSerializationVisitor $visitor, StaticPagerInterface $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    public function serializePagerViewToJson(JsonSerializationVisitor $visitor, StaticPagerInterface $pager, array $type, Context $context)
    {
        return $this->convertPagerToArray($visitor, $pager, $type, $context);
    }

    private function convertPagerToArray(GenericSerializationVisitor $visitor, StaticPagerInterface $pager, array $type, Context $context)
    {
        $isRoot = null === $visitor->getRoot();


        if ($context->attributes->containsKey('gloomy_compatibility') && (true === $context->attributes->get('gloomy_compatibility')->get())) {
            $result = array(
                'page' => $pager->getCurrentPageNumber(),
                'per_page' => $pager->getItemCountPerPage(),
            );
        } else {
            $result = array(
                'current_page_number' => $pager->getCurrentPageNumber(),
                'item_count_per_page' => $pager->getItemCountPerPage(),
            );
        }

        $result = array_merge($result, array(
            'page_count'         => $pager->getPageCount(),
            'total_item_count'   => $pager->getTotalItemCount(),
            'first_item_number'  => $pager->getFirstItemNumber(),
            'last_item_number'   => $pager->getLastItemNumber(),
            'current_item_count' => $pager->getCurrentItemCount(),
            'items'              => $visitor->getNavigator()->accept($pager->getItems(), array('name' => 'array'), $context)
        ));

        if ($isRoot) {
            $visitor->setRoot($result);
        }

        return $result;
    }
}
