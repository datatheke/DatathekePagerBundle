<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Datatheke\Bundle\PagerBundle\Pager\Handler\Http\BootstrapTypeaheadHandler as BootstrapTypeaheadPagerHandler;

class BootstrapTypeaheadHandler extends AutocompleteHandler
{
    public function __construct(array $options = array())
    {
        parent::__construct();

        $this->pagerHandler = new BootstrapTypeaheadPagerHandler($options);
    }
}
