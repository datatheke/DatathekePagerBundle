<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

interface HttpHandlerInterface
{
    public function handleRequest(PagerInterface $pager, Request $request);
}
