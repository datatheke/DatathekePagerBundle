<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Doctrine\ORM\EntityRepository;

class ORMEntityAdapter extends ORMQueryBuilderAdapter
{
    public function __construct(EntityRepository $repository, $alias = 'e')
    {
        parent::__construct($repository->createQueryBuilder($alias));
    }
}
