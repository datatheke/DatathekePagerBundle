<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\LocalizedDateTimeColumn;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException;

class LocalizedDateTimeGuesser implements GuesserInterface
{
    public function guess(Field $field, $fieldAlias)
    {
        if (Field::TYPE_DATETIME === $field->getType())
        {
            return new LocalizedDateTimeColumn($field);
        }

        throw new UnableToGuessException($field, $fieldAlias, __CLASS__);
    }
}