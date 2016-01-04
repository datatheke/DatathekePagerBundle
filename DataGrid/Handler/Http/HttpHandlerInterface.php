<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDataGridInterface;
use Symfony\Component\HttpFoundation\Request;

interface HttpHandlerInterface
{
    public function handleRequest(HttpDataGridInterface $datagrid, Request $request);
}
