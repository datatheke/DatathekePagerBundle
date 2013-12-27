<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\HttpFoundation\Request;

interface HttpPagerInterface extends PagerInterface
{
    public function handleRequest(Request $request);
}