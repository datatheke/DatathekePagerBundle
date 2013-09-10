<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Doctrine\ORM\EntityManager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\ORMEntityAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

class ORMEntityGuesser implements GuesserInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function guess($input)
    {
        if (is_string($input))
        {
            try {
                // Check repository
                $repository = $this->em->getRepository($input);
                return new ORMEntityAdapter($this->em, $input);
            }
            catch (\Exception $e) {
            }
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}