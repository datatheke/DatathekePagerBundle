<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception;

use Datatheke\Bundle\PagerBundle\Pager\Field;

class UnableToGuessException extends \Exception
{
    public function __construct(Field $field, $fieldAlias, $class, \Exception $previous = null)
    {
        parent::__construct(
            sprintf(
                'Guesser "%s" was unable to find a column for field "%s" of type "%s"',
                $class,
                $fieldAlias,
                $field->getType()
            ),
            0,
            $previous
        );
    }
}