<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Doctrine\ODM\MongoDB\DocumentRepository;

class MongoDBDocumentAdapter extends MongoDBQueryBuilderAdapter
{
    public function __construct(DocumentRepository $repository, $alias = 'e')
    {
        parent::__construct($repository->createQueryBuilder($alias));
    }
}
