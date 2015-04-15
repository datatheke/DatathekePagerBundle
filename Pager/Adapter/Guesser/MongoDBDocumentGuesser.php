<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\MongoDBDocumentAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

class MongoDBDocumentGuesser implements GuesserInterface
{
    protected $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function guess($input)
    {
        if ($input instanceof DocumentRepository) {
            return new MongoDBDocumentAdapter($input);
        } elseif (is_string($input)) {
            try {
                // Check repository
                $manager = $this->registry->getManagerForClass($input);
                if (null !== $manager) {
                    $repository = $manager->getRepository($input);

                    return new MongoDBDocumentAdapter($repository);
                }
            } catch (\Exception $e) {
            }
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
