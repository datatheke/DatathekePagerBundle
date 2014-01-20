<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\GuesserInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\HttpHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Console\ConsoleHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Console\DefaultHandler;

class Factory
{
    protected $config;
    protected $guesser;

    public function __construct(Configuration $config, GuesserInterface $guesser)
    {
        $this->config  = $config;
        $this->guesser = $guesser;
    }

    /**
     * @deprecated
     */
    public function createWebPager($adapter, array $options = array())
    {
        trigger_error('createWebPager() is deprecated. Use createHttpPager() instead.', E_USER_DEPRECATED);

        $pagerOptions = array_intersect_key($options, array('item_count_per_page' => null, 'item_count_per_page_choices' => null));
        $options      = array_diff_key($options, array('item_count_per_page' => null, 'item_count_per_page_choices' => null));

        return $this->createHttpPager($adapter, $pagerOptions, new ViewHandler($options));
    }

    public function createHttpPager($adapter, array $options = array(), $handler = null)
    {
        $adapter = $this->guessAdapter($adapter);

        if (!$handler instanceOf HttpHandlerInterface) {
            $handler = new ViewHandler();
        }

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new HttpPager($adapter, $handler, array_merge($defaults, $options));
    }

    public function createConsolePager($adapter, array $options = array(), $handler = null)
    {
        $adapter = $this->guessAdapter($adapter);

        if (!$handler instanceOf ConsoleHandlerInterface) {
            $handler = new DefaultHandler();
        }

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new ConsolePager($adapter, $handler, array_merge($defaults, $options));
    }

    protected function guessAdapter($adapter)
    {
        if ($adapter instanceOf AdapterInterface) {
            return $adapter;
        }

        return $this->guesser->guess($adapter);
    }
}
