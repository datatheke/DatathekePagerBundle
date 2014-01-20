<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\HttpFoundation\Request;

interface HttpDataGridInterface extends DataGridInterface
{
    public function handleRequest(Request $request);
}
