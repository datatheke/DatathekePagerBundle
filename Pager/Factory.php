<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\GuesserInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\HttpHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler;

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

        return $this->createHttpPager($adapter, $options);
    }

    public function createHttpPager($adapter, array $options = array(), HttpHandlerInterface $handler = null)
    {
        $adapter = $this->guessAdapter($adapter);

        if (null === $handler) {
            $handler = new ViewHandler();
        }

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new HttpPager($adapter, $handler, array_merge($defaults, $options));
    }

    public function createConsolePager($adapter, array $options = array())
    {
        $adapter = $this->guessAdapter($adapter);

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new ConsolePager($adapter, array_merge($defaults, $options));
    }

    protected function guessAdapter($adapter)
    {
        if ($adapter instanceOf AdapterInterface) {
            return $adapter;
        }

        return $this->guesser->guess($adapter);
    }
}
