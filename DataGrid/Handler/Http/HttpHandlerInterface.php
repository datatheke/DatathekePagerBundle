<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDataGridInterface;

interface HttpHandlerInterface
{
    public function handleRequest(HttpDataGridInterface $datagrid, Request $request);
}
