<?php

namespace Datatheke\Bundle\PagerBundle\Tests;

class PagerHelper
{
    public static function createPagerFactory()
    {
        $config  = new \Datatheke\Bundle\PagerBundle\Pager\Configuration(26, array(10, 20, 50), 5);
        $guesser = new \Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\ArrayGuesser();

        return new \Datatheke\Bundle\PagerBundle\Pager\Factory($config, $guesser);
    }

    public static function createDataGridFactory()
    {
        $config       = new \Datatheke\Bundle\PagerBundle\DataGrid\Configuration('fake_theme');
        $pagerFactory = self::createPagerFactory();
        $guesser      = new \Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\StringGuesser();

        return new \Datatheke\Bundle\PagerBundle\DataGrid\Factory($config, $pagerFactory, $guesser);
    }
}
