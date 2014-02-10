<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\Field;

use Doctrine\MongoDB\Query\Builder;

class MongoDBQueryBuilderAdapter implements AdapterInterface
{
    protected $builder;
    protected $fields;

    protected $count;
    protected $filter = array();
    protected $orderBy;

    public function __construct(Builder $builder, array $fields = null)
    {
        $this->builder = $builder;

        if (!is_array($fields)) {
            $this->guessFields();
        } else {
            $this->fields = $fields;
        }
    }

    public function addField(Field $field, $alias = null)
    {
        if (null !== $alias) {
            $this->fields[$alias] = $field;
        } else {
            $this->fields[] = $field;
        }
    }

    protected function guessFields()
    {
        foreach ($this->builder->getQuery()->getClass()->fieldMappings as $property => $infos) {
            switch ($infos['type']) {

                case 'one':
                case 'many':
                    $type = Field::TYPE_OBJECT;
                    $qualifier = $infos['fieldName'].'.$id';
                    continue;

                case 'date':
                    $type = Field::TYPE_DATETIME;
                    $qualifier = $infos['fieldName'];
                    break;

                case 'int':
                    $type = Field::TYPE_NUMBER;
                    $qualifier = $infos['fieldName'];
                    break;

                default:
                case 'string':
                    $type = Field::TYPE_STRING;
                    $qualifier = $infos['fieldName'];
                    break;
            }

            $this->fields[$property] = new Field($property, $type, $qualifier);
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
            $this->applyFilters($builder);

            $this->count = $builder
                ->getQuery()
                ->count()
            ;
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
        $this->applyFilters($builder);

        if (null !== $itemCountPerPage) {
            $builder->limit($itemCountPerPage);
        }

        return $builder
            ->skip($offset)
            ->getQuery()
            ->execute()
        ;
    }

    public function setFilter(Filter $filter = null, $group = 'default')
    {
        $this->filter[$group] = $filter;
        $this->count          = null;

        return $this;
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    protected function applyOrderBy(Builder $builder)
    {
        if (null === $this->orderBy) {
            return;
        }

        $builder->sort((array) $this->orderBy);
    }

    protected function applyFilters(Builder $builder)
    {
        foreach ($this->filter as $filter) {
            if (null === $filter || !count($filter->getFields())) {
                continue;
            }

            $this->doApplyFilter($builder, $filter);
        }
    }

    protected function doApplyFilter(Builder $builder, Filter $filter)
    {
        $criteria = array();
        foreach ($filter->getFields() as $key => $alias) {
            $field      = $this->getField($alias);

            $qualifier  = $field->getQualifier();
            $operator   = $filter->getOperator($key);
            $value      = $field->formatInput($filter->getValue($key));

            if (is_string($value)
                && !strlen($value)
                && !in_array($operator, array(Filter::OPERATOR_NULL, Filter::OPERATOR_NOT_NULL))
                ) {
                $criteria[] = null;
                continue;
            }

            $expr = $builder->expr();

            switch ($operator) {

                default:
                case Filter::OPERATOR_CONTAINS:
                    $criteria[] = $expr->field($qualifier)->equals(new \MongoRegex('/.*'.$value.'.*/i'));
                    break;

                case Filter::OPERATOR_NOT_CONTAINS:
                    $criteria[] = $expr->field($qualifier)->not(new \MongoRegex('/.*'.$value.'.*/i'));
                    break;

                case Filter::OPERATOR_EQUALS:
                    $criteria[] = $expr->field($qualifier)->equals($value);
                    break;

                case Filter::OPERATOR_NOT_EQUALS:
                    $criteria[] = $expr->field($qualifier)->notEqual($value);
                    break;

                case Filter::OPERATOR_GREATER:
                    $criteria[] = $expr->field($qualifier)->gt($value);
                    break;

                case Filter::OPERATOR_GREATER_EQUALS:
                    $criteria[] = $expr->field($qualifier)->gte($value);
                    break;

                case Filter::OPERATOR_LESS:
                    $criteria[] = $expr->field($qualifier)->lt($value);
                    break;

                case Filter::OPERATOR_LESS_EQUALS:
                    $criteria[] = $expr->field($qualifier)->lte($value);
                    break;

                case Filter::OPERATOR_NULL:
                    $criteria[] = $expr->field($qualifier)->exists(false);
                    break;

                case Filter::OPERATOR_NOT_NULL:
                    $criteria[] = $expr->field($qualifier)->exists(true);
                    break;

                case Filter::OPERATOR_IN:
                    if (!is_array($value)) {
                        $value = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $value, -1, PREG_SPLIT_NO_EMPTY);
                    }
                    $criteria[] = $expr->field($qualifier)->in($value);
                    break;

                case Filter::OPERATOR_NOT_IN:
                    if (!is_array($value)) {
                        $value = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $value, -1, PREG_SPLIT_NO_EMPTY);
                    }
                    $criteria[] = $expr->field($qualifier)->notIn($value);
                    break;
            }
        }

        foreach ($filter->getLogical() as $layer) {
            $criteriumIndex    = 0;
            $concatCriteria    = array();

            foreach ($layer as $log) {
                list($operator, $count) = $log;
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
                    $cond = $builder->expr();
                    foreach ($subCriteria as $cri) {
                        if (Filter::LOGICAL_OR === $operator) {
                            $cond->addOr($cri);
                        } else {
                            $cond->addAnd($cri);
                        }
                    }
                    $concatCriteria[] = $cond;
                } else {
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
            $builder->addAnd($criterium);
        }

        return $this;
    }

    protected function getField($alias)
    {
        if (!isset($this->fields[$alias])) {
            throw new \Exception('Unknown alias '.$alias);
        }

        return $this->fields[$alias];
    }
}
