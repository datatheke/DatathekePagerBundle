<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\MongoDBDocumentAdapter;
use Datatheke\Bundle\PagerBundle\Pager\Adapter\Guesser\Exception\UnableToGuessException;

class MongoDBDocumentGuesser implements GuesserInterface
{
    protected $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function guess($input)
    {
        if (is_object($input) && $input instanceof DocumentRepository) {
            return new MongoDBDocumentAdapter($input);
        } elseif (is_string($input)) {
            try {
                // Check repository
                $repository = $this->dm->getRepository($input);

                return new MongoDBDocumentAdapter($repository);
            } catch (\Exception $e) {
            }
        }

        throw new UnableToGuessException($input, __CLASS__);
    }
}
