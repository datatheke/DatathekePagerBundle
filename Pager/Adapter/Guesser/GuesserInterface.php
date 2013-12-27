<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

interface GuesserInterface
{
    /**
     * @return Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface
     *                                                                     @throw Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException
     */
    public function guess($input);
}
