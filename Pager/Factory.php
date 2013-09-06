<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Configuration;
use Datatheke\Bundle\PagerBundle\Pager\Pager;
use Datatheke\Bundle\PagerBundle\Pager\WebPager;
use Datatheke\Bundle\PagerBundle\Pager\ConsolePager;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\GuesserInterface;

class Factory
{
    protected $config;
    protected $guesser;

    public function __construct(Configuration $config, GuesserInterface $guesser)
    {
        $this->config  = $config;
        $this->guesser = $guesser;
    }

    public function createPager($adapter, $itemCountPerPage = null, $currentPageNumber = 1)
    {
        $adapter = $this->guessAdapter($adapter);

        if (null === $itemCountPerPage) {
            $itemCountPerPage = $this->config->getItemCountPerPage();
        }

        return new Pager($adapter, $itemCountPerPage, $currentPageNumber);
    }

    public function createWebPager($adapter, array $options = array())
    {
        $adapter = $this->guessAdapter($adapter);

        $defaults = array(
            'item_count_per_page'         => $this->config->getItemCountPerPage(),
            'item_count_per_page_choices' => $this->config->getItemCountPerPageChoices()
        );

        return new WebPager($adapter, array_merge($defaults, $options));
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
