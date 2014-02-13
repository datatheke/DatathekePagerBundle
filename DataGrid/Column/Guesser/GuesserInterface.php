<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Field;

interface GuesserInterface
{
    /**
     * @return Datatheke\Bundle\PagerBundle\DataGrid\Column\ColumnInterface
     *
     * @throws Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException
     */
    public function guess(Field $field, $fieldAlias);
}
