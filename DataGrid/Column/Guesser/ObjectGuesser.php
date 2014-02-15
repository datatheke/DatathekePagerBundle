<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\ObjectColumn;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException;

class ObjectGuesser implements GuesserInterface
{
    public function guess(Field $field, $fieldAlias)
    {
        if (Field::TYPE_OBJECT === $field->getType()) {
            $column = new ObjectColumn($field);

            $metadatas = $field->getMetadatas();
            if (isset($metadatas['repository'])) {
                $column->setObjectRepository($metadatas['repository']);
            }

            return $column;
        }

        throw new UnableToGuessException($field, $fieldAlias, __CLASS__);
    }
}
