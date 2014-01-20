<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\ORMQueryBuilderAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

use Doctrine\ORM\QueryBuilder;

class ORMQueryBuilderGuesser implements GuesserInterface
{
    public function guess($input)
    {
        if (is_object($input) && $input instanceof QueryBuilder) {
            return new ORMQueryBuilderAdapter($input);
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
