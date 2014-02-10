<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;

use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\BootstrapTypeaheadHandler as BootstrapTypeaheadPagerHandler;

class BootstrapTypeaheadHandler extends AutocompleteHandler
{
    public function __construct(array $options = array())
    {
        $this->pagerHandler = new BootstrapTypeaheadPagerHandler($options);
    }
}
