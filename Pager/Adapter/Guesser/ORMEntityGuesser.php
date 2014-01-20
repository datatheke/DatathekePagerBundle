<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

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
        if (is_object($input) && $input instanceOf EntityRepository) {
            return new ORMEntityAdapter($input);
        } elseif (is_string($input)) {
            try {
                // Check repository
                $repository = $this->em->getRepository($input);

                return new ORMEntityAdapter($repository);
            } catch (\Exception $e) {
            }
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
