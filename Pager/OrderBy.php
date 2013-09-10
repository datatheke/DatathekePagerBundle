<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Exception\InvalidOrderByException;

class OrderBy extends \ArrayObject
{
    const ASC  = 'asc';
    const DESC = 'desc';

    public function __construct(array $orderBy = array())
    {
        foreach ($orderBy as $field => $order) {
            if (!is_string($field) || ($order !== self::ASC && $order !== self::DESC)) {
                throw new InvalidOrderByException($field, $order);
            }
        }

        parent::__construct($orderBy);
    }
}