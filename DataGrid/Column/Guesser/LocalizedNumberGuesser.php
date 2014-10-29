<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\LocalizedNumberColumn;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException;

class LocalizedNumberGuesser implements GuesserInterface
{
    public function guess(Field $field, $fieldAlias)
    {
        if (Field::TYPE_NUMBER === $field->getType()) {
            $column = new LocalizedNumberColumn($field);

            $metadata = $field->getMetadata();
            if (isset($metadata['precision'])) {
                $column->setPrecision($metadata['precision']);
            }

            return $column;
        }

        throw new UnableToGuessException($field, $fieldAlias, __CLASS__);
    }
}
