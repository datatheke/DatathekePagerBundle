<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\Field;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;

class ORMQueryBuilderAdapter implements AdapterInterface
{
    protected $builder;
    protected $fields;

    protected $count;
    protected $filter;
    protected $orderBy;

    public function __construct(QueryBuilder $builder, array $fields = null)
    {
        $this->builder = $builder;

        if (!is_array($fields)) {
            $this->guessFields();
        }
        else {
            $this->fields = $fields;
        }
    }

    protected function guessFields()
    {
        $em = $this->builder->getEntityManager();
        $this->builder->getRootEntities(); // Force rebuild 'from' part

        $entities = array();
        foreach ($this->builder->getDQLPart('from') as $fromClause) {
            $entities[$fromClause->getAlias()] = $fromClause->getFrom();
        }

        foreach ($entities as $alias => $entity) {
            $metas = $em->getClassMetadata($entity);
            foreach ($metas->fieldMappings as $property => $infos) {
                switch ($infos['type']) {
                    case 'datetime':
                        $type = Field::TYPE_DATETIME;
                        break;

                    default:
                        $type = Field::TYPE_STRING;
                        break;
                }
                $this->fields[$property] = new Field($property, $type, $alias.'.'.$property);
            }
        }
    }

    public function getQueryBuilder()
    {
        return $this->builder;
    }

    public function count()
    {
        if (null === $this->count) {
            $builder = clone $this->builder;
            $this->applyFilter($builder);

            $this->count = $builder
                ->select('COUNT(DISTINCT '.$builder->getRootAlias().')')
                ->resetDQLPart('orderBy')
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $this->count;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getItems($offset = 0, $itemCountPerPage = null)
    {
        $builder = clone $this->builder;
        $this->applyOrderBy($builder);
        $this->applyFilter($builder);

        if (null !== $itemCountPerPage) {
            $builder->setMaxResults($itemCountPerPage);
        }

        return $builder
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    public function setFilter(Filter $filter = null)
    {
        $this->filter = $filter;
        $this->count  = null;

        return $this;
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        $this->orderBy   = $orderBy;

        return $this;
    }

    protected function applyOrderBy(QueryBuilder $builder)
    {
        if (null === $this->orderBy) {
            return;
        }

        $first = true;
        foreach ($this->orderBy as $alias => $order) {
            $sort = $this->getField($alias)->getQualifier();
            if ($first) {
                $builder->orderBy($sort, $order);
                $first = false;
            }
            else {
                $builder->addOrderBy($sort, $order);
            }
        }
    }

    protected function applyFilter(QueryBuilder $builder)
    {
        if (null === $this->filter || !count($this->filter->getFields())) {
            return;
        }

        $criteria = array();
        $expr     = $builder->expr();
        $paramNum = 0;
        foreach ($this->filter->getFields() as $key => $alias) {

            $paramName  = 'param_'.$paramNum++;
            $field      = $this->getField($alias);

            $qualifier  = $field->getQualifier();
            $operator   = $this->filter->getOperator($key);
            $value      = $field->formatInput($this->filter->getValue($key));

            if (is_string($value)
                && !strlen($value)
                && !in_array($operator, array(Filter::OPERATOR_NULL, Filter::OPERATOR_NOT_NULL))
                ) {
                $criteria[] = null;
                continue;
            }

            switch ($operator) {

                default:
                case Filter::OPERATOR_CONTAINS:
                    // if (isset($this->_config['force_case_insensitive'])) { // Force case insensitive (ie. for Oracle)
                    //     $criteria[] = $expr->like('UPPER('.$qualifier.')', ':'.$paramName);
                    //     $builder->setParameter($paramName, strtoupper('%'.$value.'%'));
                    // }
                    // else {
                        $criteria[] = $expr->like($qualifier, ':'.$paramName);
                        $builder->setParameter($paramName, '%'.$value.'%');
                    // }
                    break;

                case Filter::OPERATOR_NOT_CONTAINS:
                    // if (isset($this->_config['force_case_insensitive'])) { // Force case insensitive (ie. for Oracle)
                    //     $criteria[] = $expr->not($expr->like('UPPER('.$qualifier.')', ':'.$paramName));
                    //     $builder->setParameter($paramName, strtoupper('%'.$value.'%'));
                    // }
                    // else {
                        $criteria[] = $expr->not($expr->like($qualifier, ':'.$paramName));
                        $builder->setParameter($paramName, '%'.$value.'%');
                    // }
                    break;

                case Filter::OPERATOR_EQUALS:
                    $criteria[] = $expr->eq($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_NOT_EQUALS:
                    $criteria[] = $expr->neq($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_GREATER:
                    $criteria[] = $expr->gt($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_GREATER_EQUALS:
                    $criteria[] = $expr->gte($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_LESS:
                    $criteria[] = $expr->lt($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_LESS_EQUALS:
                    $criteria[] = $expr->lte($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_NULL:
                    $criteria[] = $expr->isNull($qualifier);
                    break;

                case Filter::OPERATOR_NOT_NULL:
                    $criteria[] = $expr->isNotNull($qualifier);
                    break;

                case Filter::OPERATOR_IN:
                    if (!is_array($value)) {
                        $value = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $value, -1, PREG_SPLIT_NO_EMPTY);
                    }
                    $criteria[] = $expr->in($qualifier, ':'.$paramName);
                    $builder->setParameter($paramName, $value);
                    break;

                case Filter::OPERATOR_NOT_IN:
                    if (!is_array($value)) {
                        $value = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $value, -1, PREG_SPLIT_NO_EMPTY);
                    }
                    $criteria[] = $expr->not($expr->in($qualifier, ':'.$paramName));
                    $builder->setParameter($paramName, $value);
                    break;
            }
        }

        foreach ($this->filter->getLogical() as $layer) {
            $criteriumIndex    = 0;
            $concatCriteria    = array();
            foreach ($layer as $operator => $count) {
                if (null === $count) {
                    $count = count($criteria) - $criteriumIndex;
                }

                // Criteria for the operator
                $subCriteria   = array();
                foreach (array_slice($criteria, $criteriumIndex, $count) as $criterium) {
                    if ($criterium) {
                        $subCriteria[] = $criterium;
                    }
                }
                $criteriumIndex += $count;

                // Apply operator
                if (count($subCriteria) > 1) {
                    $method    = (Filter::LOGICAL_OR === $operator) ? 'orX' : 'andX';
                    $concatCriteria[] = ($subCriteria) ? call_user_func_array(array($builder->expr(), $method), $subCriteria)
                                                       : null;
                }
                else {
                    $concatCriteria[] = current($subCriteria);
                }

                // Complete array
                for ($count--; $count; $count--) {
                    $concatCriteria[] = null;
                }
            }
            $criteria    = $concatCriteria;
        }

        $criterium    = current($criteria);
        if ($criterium) {
            $builder->andWhere($criterium);
        }

        return $this;
    }

    protected function getField($alias)
    {
        if (!isset($this->fields[$alias])) {
            $alias = $this->builder->getRootAlias().'.'.$alias; // if alias does not exist, try with default object alias
        }
        if (!isset($this->fields[$alias])) {
            throw new \Exception('Unknown alias '.$alias);
        }
        return $this->fields[$alias];
    }
}
