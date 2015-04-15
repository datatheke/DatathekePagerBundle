<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\HttpFoundation\Request;
use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http\HttpHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

class HttpDataGrid extends DataGrid implements HttpDataGridInterface
{
    protected $handler;

    public function __construct(PagerInterface $pager, HttpHandlerInterface $handler, array $columns, array $options = array())
    {
        $this->handler = $handler;

        parent::__construct($pager, $columns, $options);
    }

    public function handleRequest(Request $request)
    {
        $this->initialize();

        return $this->handler->handleRequest($this, $request);
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
