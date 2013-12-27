<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\StringColumn;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException;

class StringGuesser implements GuesserInterface
{
    public function guess(Field $field, $fieldAlias)
    {
        if (Field::TYPE_STRING === $field->getType()) {
            return new StringColumn($field);
        }

        throw new UnableToGuessException($field, $fieldAlias, __CLASS__);
    }
}
