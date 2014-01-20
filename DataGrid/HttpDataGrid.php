<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\HttpHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

class HttpDataGrid extends DataGrid implements HttpDataGridInterface
{
    protected $handler;

    public function __construct(PagerInterface $pager, HttpHandlerInterface $handler, array $columns = null)
    {
        $this->handler = $handler;

        parent::__construct($pager, $columns);
    }

    public function handleRequest(Request $request)
    {
        $this->initialize();

        return $this->handler->handleRequest($this, $request);
    }
}
