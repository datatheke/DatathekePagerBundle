<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class ORMEntityAdapter extends ORMQueryBuilderAdapter
{
    public function __construct(EntityManager $em, $entity)
    {
        parent::__construct($em->getRepository($entity)->createQueryBuilder('e'));
    }
}
