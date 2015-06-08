<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\PagerFantaAdapter;
use Pagerfanta\Adapter\AdapterInterface as PagerFantaAdapterInterface;

class PagerFantaGuesser implements GuesserInterface
{
    public function guess($input)
    {
        if ($input instanceof PagerFantaAdapterInterface) {
            return new PagerFantaAdapter($input);
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
