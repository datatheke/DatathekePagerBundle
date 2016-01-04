<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Symfony\Component\HttpFoundation\Request;

interface HttpHandlerInterface
{
    public function handleRequest(PagerInterface $pager, Request $request);
}
