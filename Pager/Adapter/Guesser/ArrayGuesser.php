<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\ArrayAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

class ArrayGuesser implements GuesserInterface
{
    public function guess($input)
    {
        if (is_array($input)) {
            return new ArrayAdapter($input);
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
