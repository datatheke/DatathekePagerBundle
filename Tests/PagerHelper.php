<?php

namespace Datatheke\Bundle\PagerBundle\Tests;

class PagerHelper
{
    public static function createPagerFactory()
    {
        $config  = new \Datatheke\Bundle\PagerBundle\Pager\Configuration(26, array(10, 20, 50), 5);
        $guesser = new \Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\ArrayGuesser();

        $httpHandlers = array('view' => new \Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler());
        $consoleHandlers = array('default' => new \Datatheke\Bundle\PagerBundle\Pager\Handler\Console\DefaultHandler());

        return new \Datatheke\Bundle\PagerBundle\Pager\Factory($config, $guesser, $httpHandlers, $consoleHandlers);
    }

    public static function createDataGridFactory()
    {
        $config       = new \Datatheke\Bundle\PagerBundle\DataGrid\Configuration('fake_theme');
        $pagerFactory = self::createPagerFactory();
        $guesser      = new \Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\StringGuesser();

        $httpHandlers = array('view' => new \Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\ViewHandler());
        $consoleHandlers = array('default' => new \Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console\DefaultHandler());

        return new \Datatheke\Bundle\PagerBundle\DataGrid\Factory($config, $pagerFactory, $guesser, $httpHandlers, $consoleHandlers);
    }
}
