<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\GuesserInterface;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

class GuesserChain implements GuesserInterface
{
    protected $guessers = array();

    public function addGuesser(GuesserInterface $guesser)
    {
        $this->guessers[] = $guesser;
    }

    public function guess($input)
    {
        $exception = null;
        foreach ($this->guessers as $guesser) {
            try {
                return $guesser->guess($input);
            }
            catch (UnableToGuessException $exception) {
            }
        }

        throw new UnableToGuessException($input, __CLASS__, $exception);
    }
}