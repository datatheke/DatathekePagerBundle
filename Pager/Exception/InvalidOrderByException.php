<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Exception;

class InvalidOrderByException extends \Exception
{
    public function __construct($field, $order)
    {
        parent::__construct(
            sprintf('Invalid OrderBy value')
        );
    }
}