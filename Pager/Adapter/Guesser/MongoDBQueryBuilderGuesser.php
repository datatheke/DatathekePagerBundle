<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\MongoDBQueryBuilderAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

use Doctrine\MongoDB\Query\Builder;

class MongoDBQueryBuilderGuesser implements GuesserInterface
{
    public function guess($input)
    {
        if (is_object($input) && $input instanceOf Builder) {
            return new MongoDBQueryBuilderAdapter($input);
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
