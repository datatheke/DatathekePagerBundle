<?php

namespace Datatheke\Bundle\PagerBundle\Tests\Pager\Adapter;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\ORMQueryBuilderAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Tests\Entity\Person;

class ORMQueryBuilderAdapterTest extends \PHPUnit_Framework_TestCase
{
    public $em;

    public function setUp()
    {
        if (!class_exists('Doctrine\ORM\EntityManager')) {
            $this->markTestSkipped('Doctrine ORM is not available');
        }

        $this->em = $this->getEntityManager();
        $this->loadFixtures();
    }

    protected function getEntityManager()
    {
        $config = new Configuration();
        $config->setMetadataCacheImpl(new ArrayCache());
        $config->setQueryCacheImpl(new ArrayCache());
        $config->setProxyDir(__DIR__.'/cache');
        $config->setProxyNamespace('Cache\Proxies');

        $driver = new AnnotationDriver(new AnnotationReader(), array(__DIR__.'../../Entity'));
        AnnotationRegistry::registerLoader('class_exists');
        $config->setMetadataDriverImpl($driver);

        $conn = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        return EntityManager::create($conn, $config);
    }

    protected function loadFixtures()
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->createSchema(array(
            $this->em->getClassMetadata('Datatheke\Bundle\PagerBundle\Tests\Entity\Person'),
        ));

        $person1 = new Person();
        $person1->firstname = 'john';
        $person1->lastname = 'doe';
        $person1->age = 32;
        $person1->friend = true;
        $person1->birthday = \DateTime::createFromFormat('Y-m-d H:i:s', '1982-12-03 22:10:09');

        $person2 = new Person();
        $person2->firstname = 'jean';
        $person2->lastname = 'bon';
        $person2->age = 25;
        $person1->friend = false;
        $person2->birthday = \DateTime::createFromFormat('Y-m-d H:i:s', '1983-09-27 10:43:32');

        $this->em->persist($person1);
        $this->em->persist($person2);
        $this->em->flush();
    }

    public function testGuessFields()
    {
        $qb = $this->em
            ->getRepository('Datatheke\Bundle\PagerBundle\Tests\Entity\Person')
            ->createQueryBuilder('e')
        ;
        $adapter = new ORMQueryBuilderAdapter($qb);
        $fields = $adapter->getFields();

        $this->assertEquals('e.firstname', $fields['firstname']->getQualifier());
        $this->assertEquals(Field::TYPE_NUMBER, $fields['age']->getType());
        $this->assertEquals(Field::TYPE_BOOLEAN, $fields['friend']->getType());
    }

    public function testOrderBy()
    {
        $qb = $this->em
            ->getRepository('Datatheke\Bundle\PagerBundle\Tests\Entity\Person')
            ->createQueryBuilder('e')
        ;
        $adapter = new ORMQueryBuilderAdapter($qb);

        $adapter->setOrderBy(new OrderBy(array('age' => OrderBy::ASC)));
        $items = $adapter->getItems();
        $this->assertEquals('bon', $items[0]->lastname);
        $this->assertEquals('doe', $items[1]->lastname);

        $adapter->setOrderBy(new OrderBy(array('age' => OrderBy::DESC)));
        $items = $adapter->getItems();
        $this->assertEquals('bon', $items[1]->lastname);
        $this->assertEquals('doe', $items[0]->lastname);
    }

    public function testFilter()
    {
        $qb = $this->em
            ->getRepository('Datatheke\Bundle\PagerBundle\Tests\Entity\Person')
            ->createQueryBuilder('e')
        ;
        $adapter = new ORMQueryBuilderAdapter($qb);

        $this->assertEquals(2, $adapter->count());
        $adapter->setFilter(new Filter(array('lastname'), array('bon'), array(Filter::OPERATOR_EQUALS)));
        $this->assertEquals(1, $adapter->count());

        $items = $adapter->getItems();
        $this->assertEquals(25, $items[0]->age);
    }

    public function testFilterFromArray()
    {
        $qb = $this->em
            ->getRepository('Datatheke\Bundle\PagerBundle\Tests\Entity\Person')
            ->createQueryBuilder('e')
        ;
        $adapter = new ORMQueryBuilderAdapter($qb);

        $filter = array(
            'operator' => Filter::LOGICAL_AND,
            'criteria' => array(
                array(
                    'field' => 'lastname',
                    'operator' => Filter::OPERATOR_CONTAINS,
                    'value' => 'bon',
                ),
                array(
                    'field' => 'firstname',
                    'operator' => Filter::OPERATOR_CONTAINS,
                    'value' => '',
                ),
                array(
                    'field' => 'birthday',
                    'operator' => Filter::OPERATOR_CONTAINS,
                    'value' => null,
                ),
            ),
        );

        $adapter->setFilter(Filter::createFromArray($filter));

        $items = $adapter->getItems();

        $this->assertEquals(1, $adapter->count());
        $this->assertEquals('jean', $items[0]->firstname);
    }

    public function testFilterWithCallableQualifier()
    {
        $qb = $this->em
            ->getRepository('Datatheke\Bundle\PagerBundle\Tests\Entity\Person')
            ->createQueryBuilder('e')
        ;
        $adapter = new ORMQueryBuilderAdapter($qb);

        $adapter->addField(new Field('id', Field::TYPE_NUMBER, function ($value, $operator, $builder) {
            return $builder->expr()->eq('(e.id * '.$value.')', '10');
        }), 'id');

        $this->assertEquals(2, $adapter->count());
        $adapter->setFilter(new Filter(array('id'), array(5)));
        $this->assertEquals(1, $adapter->count());
    }
}
