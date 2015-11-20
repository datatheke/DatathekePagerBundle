<?php

namespace Datatheke\Bundle\PagerBundle\Tests;

class PagerHelper
{
    protected static $persons = array(
        array('firstname' => 'jean', 'lastname' => 'bon', 'age' => 32),
        array('firstname' => 'claude', 'lastname' => 'chouette', 'age' => 22),
        array('firstname' => 'aurélie', 'lastname' => 'vélo', 'age' => 18),
        array('firstname' => 'yves', 'lastname' => 'bon', 'age' => 32),
        array('firstname' => 'paul', 'lastname' => 'jesus', 'age' => 55),
        array('firstname' => 'jean', 'lastname' => 'bon', 'age' => 17),
        array('firstname' => 'sophie', 'lastname' => 'bon', 'age' => 17),
        array('firstname' => 'jean', 'lastname' => 'veux', 'age' => 18),
        array('firstname' => 'marc', 'lastname' => 'jean', 'age' => 26),
        array('firstname' => 'steve', 'lastname' => 'bon', 'age' => 27),
        array('firstname' => 'verycoolfirstname', 'lastname' => 'verycoollastname', 'age' => 8),
        array('firstname' => 'jean', 'lastname' => 'doublon', 'age' => 99),
        );

    public static function createPagerFactory()
    {
        $config = new \Datatheke\Bundle\PagerBundle\Pager\Configuration(26, array(10, 20, 50), 5);
        $guesser = new \Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\ArrayGuesser();

        $httpHandlers = array('view' => new \Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler());
        $consoleHandlers = array('default' => new \Datatheke\Bundle\PagerBundle\Pager\Handler\Console\DefaultHandler());

        return new \Datatheke\Bundle\PagerBundle\Pager\Factory($config, $guesser, $httpHandlers, $consoleHandlers);
    }

    public static function createDataGridFactory()
    {
        $config = new \Datatheke\Bundle\PagerBundle\DataGrid\Configuration('fake_theme');
        $pagerFactory = self::createPagerFactory();
        $guesser = new \Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\StringGuesser();

        $httpHandlers = array('view' => new \Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\ViewHandler());
        $consoleHandlers = array('default' => new \Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console\DefaultHandler());

        return new \Datatheke\Bundle\PagerBundle\DataGrid\Factory($config, $pagerFactory, $guesser, $httpHandlers, $consoleHandlers);
    }

    public static function createPager()
    {
        return self::createPagerFactory()->createHttpPager(self::getPersons());
    }

    public static function createDataGrid()
    {
        return self::createDataGridFactory()->createHttpDataGrid(self::getPersons());
    }

    public static function getPersons()
    {
        return self::$persons;
    }
}
