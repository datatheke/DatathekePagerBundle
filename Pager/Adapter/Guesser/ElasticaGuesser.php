<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\ElasticaAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;
use Elastica\SearchableInterface;

class ElasticaGuesser implements GuesserInterface
{
    public function guess($input)
    {
        if ($input instanceof SearchableInterface) {
            return new ElasticaAdapter($input);
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
