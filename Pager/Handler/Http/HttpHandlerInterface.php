<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\HttpPagerInterface;

interface HttpHandlerInterface
{
    public function handleRequest(HttpPagerInterface $pager, Request $request);
}