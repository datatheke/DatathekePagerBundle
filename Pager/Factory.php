<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\GuesserInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\HttpHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Console\ConsoleHandlerInterface;

class Factory
{
    protected $config;
    protected $guesser;
    protected $httpHandlers;
    protected $consoleHandlers;

    public function __construct(Configuration $config, GuesserInterface $guesser, array $httpHandlers, array $consoleHandlers)
    {
        $this->config          = $config;
        $this->guesser         = $guesser;
        $this->httpHandlers    = $httpHandlers;
        $this->consoleHandlers = $consoleHandlers;
    }

    public function createPager($adapter, array $options = array())
    {
        if (!$adapter instanceof AdapterInterface) {
            $adapter = $this->guessAdapter($adapter);
        }

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new Pager($adapter, array_merge($defaults, $options));
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

    public function createHttpPager($adapter, array $options = array(), $handler = 'view')
    {
        if (!$adapter instanceof AdapterInterface) {
            $adapter = $this->guessAdapter($adapter);
        }

        if (!$handler instanceof HttpHandlerInterface) {
            $handler = $this->createHandler($handler, $this->httpHandlers);
        }

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new HttpPager($adapter, $handler, array_merge($defaults, $options));
    }

    public function createConsolePager($adapter, array $options = array(), $handler = 'default')
    {
        if (!$adapter instanceof AdapterInterface) {
            $adapter = $this->guessAdapter($adapter);
        }

        if (!$handler instanceof ConsoleHandlerInterface) {
            $handler = $this->createHandler($handler, $this->consoleHandlers);
        }

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new ConsolePager($adapter, $handler, array_merge($defaults, $options));
    }

    protected function createHandler($name, $list)
    {
        if (!isset($list[$name])) {
            throw new \Exception('The handler "'.$name.'" does not exist');
        }

        return $list[$name];
    }

    protected function guessAdapter($adapter)
    {
        return $this->guesser->guess($adapter);
    }
}
