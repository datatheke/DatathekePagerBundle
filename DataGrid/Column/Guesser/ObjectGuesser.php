<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\ObjectColumn;
use Datatheke\Bundle\PagerBundle\Pager\Field;

class ObjectGuesser implements GuesserInterface
{
    public function guess(Field $field, $fieldAlias)
    {
        if (Field::TYPE_OBJECT === $field->getType()) {
            $column = new ObjectColumn($field);

            $metadata = $field->getMetadata();
            if (isset($metadata['repository'])) {
                $column->setObjectRepository($metadata['repository']);
            }

            return $column;
        }

        throw new UnableToGuessException($field, $fieldAlias, __CLASS__);
    }
}
