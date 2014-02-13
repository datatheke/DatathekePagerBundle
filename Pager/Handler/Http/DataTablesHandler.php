<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

class DataTablesHandler extends AbstractHandler
{
    public function handleRequest(PagerInterface $pager, Request $request)
    {
        if ($this->has($request, 'iDisplayLength')) {
            $pager->setItemCountPerPage($this->get($request, 'iDisplayLength'));
        }

        if ($this->has($request, 'iDisplayStart')) {
            $page = ($this->get($request, 'iDisplayStart', 0) / $pager->getItemCountPerPage()) + 1;
            $pager->setCurrentPageNumber($page);
        }

        if ($this->has($request, 'sSearch') && strlen($query = $this->get($request, 'sSearch'))) {
            $this->search($pager, $query);
        }
    }
}
