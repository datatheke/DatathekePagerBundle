<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser;

use Datatheke\Bundle\PagerBundle\Pager\Field;

use Datatheke\Bundle\PagerBundle\DataGrid\Column\Guesser\Exception\UnableToGuessException;

class GuesserChain implements GuesserInterface
{
    protected $guessers = array();

    public function addGuesser(GuesserInterface $guesser)
    {
        $this->guessers[] = $guesser;
    }

    public function guess(Field $field, $fieldAlias)
    {
        $exception = null;
        foreach ($this->guessers as $guesser) {
            try {
                return $guesser->guess($field, $fieldAlias);
            } catch (UnableToGuessException $exception) {
            }
        }

        throw new UnableToGuessException($field, $fieldAlias, __CLASS__, $exception);
    }
}
