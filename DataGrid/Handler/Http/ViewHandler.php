<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Datatheke\Bundle\PagerBundle\DataGrid\DataGridView;
use Datatheke\Bundle\PagerBundle\DataGrid\HttpDataGridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler as PagerViewHandler;
use Datatheke\Bundle\PagerBundle\Pager\PagerView;
use Symfony\Component\HttpFoundation\Request;

class ViewHandler implements HttpHandlerInterface
{
    protected $pagerHandler;

    public function __construct($options = array())
    {
        if ($options instanceof PagerViewHandler) {
            $this->pagerHandler = $options;
        } elseif (is_array($options)) {
            $this->pagerHandler = new PagerViewHandler($options);
        } else {
            throw new \Exception('ViewHandler::__construct() first agrument should be an array or a Datatheke\Bundle\PagerBundle\Pager\Handler\Http\ViewHandler object');
        }
    }

    public function setOption($option, $value)
    {
        $this->pagerHandler->setOption($option, $value);

        return $this;
    }

    public function handleRequest(HttpDataGridInterface $datagrid, Request $request)
    {
        $pager = $datagrid->getPager();
        $this->pagerHandler->handleRequest($pager, $request);

        return $this->createView($datagrid, $request);
    }

    protected function createView(HttpDataGridInterface $datagrid, Request $request)
    {
        return new DataGridView($datagrid, new PagerView($datagrid->getPager(), $this->pagerHandler));
    }
}
