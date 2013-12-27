<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception;

class UnableToGuessException extends \Exception
{
    public function __construct($input, $class, \Exception $previous = null)
    {
        parent::__construct(
            sprintf(
                'Guesser "%s" was unable to find an adapter for input type "%s"',
                $class,
                is_object($input) ? get_class($input) : gettype($input)
            ),
            0,
            $previous
        );
    }
}
