<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Serializer\Handler;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializationContext;

use Datatheke\Bundle\PagerBundle\Serializer\Handler\PagerHandler;
use Datatheke\Bundle\PagerBundle\Tests\PagerHelper;

class PagerHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = PagerHelper::createDataGridFactory();
    }

    public function testSerializePagerJson()
    {
        $pager = PagerHelper::createPager();

        $serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new PagerHandler());
            })
            ->build()
        ;

        $this->assertEquals(
            '{"current_page_number":1,"item_count_per_page":26,"page_count":1,"total_item_count":12,"first_item_number":1,"last_item_number":12,"current_item_count":12,"items":[{"firstname":"jean","lastname":"bon","age":32},{"firstname":"claude","lastname":"chouette","age":22},{"firstname":"aur\u00e9lie","lastname":"v\u00e9lo","age":18},{"firstname":"yves","lastname":"bon","age":32},{"firstname":"paul","lastname":"jesus","age":55},{"firstname":"jean","lastname":"bon","age":17},{"firstname":"sophie","lastname":"bon","age":17},{"firstname":"jean","lastname":"veux","age":18},{"firstname":"marc","lastname":"jean","age":26},{"firstname":"steve","lastname":"bon","age":27},{"firstname":"verycoolfirstname","lastname":"verycoollastname","age":8},{"firstname":"jean","lastname":"doublon","age":99}]}',
            $serializer->serialize($pager, 'json')
        );

        $this->assertEquals(
            '{"page":1,"per_page":26,"page_count":1,"total_item_count":12,"first_item_number":1,"last_item_number":12,"current_item_count":12,"items":[{"firstname":"jean","lastname":"bon","age":32},{"firstname":"claude","lastname":"chouette","age":22},{"firstname":"aur\u00e9lie","lastname":"v\u00e9lo","age":18},{"firstname":"yves","lastname":"bon","age":32},{"firstname":"paul","lastname":"jesus","age":55},{"firstname":"jean","lastname":"bon","age":17},{"firstname":"sophie","lastname":"bon","age":17},{"firstname":"jean","lastname":"veux","age":18},{"firstname":"marc","lastname":"jean","age":26},{"firstname":"steve","lastname":"bon","age":27},{"firstname":"verycoolfirstname","lastname":"verycoollastname","age":8},{"firstname":"jean","lastname":"doublon","age":99}]}',
            $serializer->serialize($pager, 'json', SerializationContext::create()->setAttribute('gloomy_compatibility', true))
        );
    }
}
