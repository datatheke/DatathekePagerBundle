<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\ORMEntityAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

class ORMEntityGuesser implements GuesserInterface
{
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function guess($input)
    {
        if ($input instanceof EntityRepository) {
            return new ORMEntityAdapter($input);
        } elseif (is_string($input)) {
            try {
                // Check repository
                $manager = $this->registry->getManagerForClass($input);
                if (null !== $manager) {
                    $repository = $manager->getRepository($input);

                    return new ORMEntityAdapter($repository);
                }
            } catch (\Exception $e) {
            }
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
