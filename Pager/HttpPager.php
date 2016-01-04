<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\HttpHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class HttpPager extends Pager implements HttpPagerInterface
{
    protected $handler;

    public function __construct(AdapterInterface $adapter, HttpHandlerInterface $handler, array $options = array())
    {
        $this->handler = $handler;

        parent::__construct($adapter, $options);
    }

    public function handleRequest(Request $request)
    {
        return $this->handler->handleRequest($this, $request);
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
