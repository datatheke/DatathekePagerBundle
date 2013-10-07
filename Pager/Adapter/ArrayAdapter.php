<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\Field;

class ArrayAdapter implements AdapterInterface
{
    protected $fields;
    protected $source;
    protected $copy;

    protected $filter = array();
    protected $orderBy;
    protected $isOrdered;

    public function __construct(array $source, array $fields = null)
    {
        $this->source    = $source;
        $this->isOrdered = true;

        if (!is_array($fields)) {
            $this->guessFields();
        }
        else {
            $this->fields = $fields;
        }
    }

    protected function guessFields()
    {
        foreach (current($this->source) as $key => $val) {
            $type      = Field::TYPE_STRING;
            $metadatas = array();

            if ($val instanceOf \DateTime) {
                $type = Field::TYPE_DATETIME;
            }
            else {
                switch (gettype($val)) {

                    case 'integer':
                        $type      = Field::TYPE_NUMBER;
                        $metadatas = array('precision' => 0);
                        break;

                    case 'double':
                        $type = Field::TYPE_NUMBER;
                        break;
                }
            }

            $this->fields[$key] = new Field('['.$key.']', $type, $key, $metadatas);
        }
    }

    public function count()
    {
        return count($this->getArray());
    }

    public function getItems($offset = 0, $itemCountPerPage = null)
    {
        $array = &$this->getArray();
        if (false === $this->isOrdered && null !== $this->orderBy) {
            $this->applyOrderBy($array);
        }
        return array_slice($array, $offset, $itemCountPerPage);
    }

    public function getFields()
    {
        return $this->fields;
    }

    protected function getField($alias)
    {
        return $this->fields[$alias];
    }

    public function setFilter(Filter $filter = null, $group = 'default')
    {
        $this->filter[$group] = $filter;
        $this->copy           = null;
        $this->isOrdered      = false;

        return $this;
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        $this->orderBy   = $orderBy;
        $this->isOrdered = false;

        return $this;
    }

    protected function &getArray()
    {
        // Works on source if there is no filter
        if (count($this->filter)) {
            return $this->source;
        }

        // Apply filter if it has not been done yet
        if (null === $this->copy) {
            $this->applyFilters();
        }

        return $this->copy;
    }

    protected function applyOrderBy(&$array)
    {
        $sortArray = array();
        foreach ($this->orderBy as $alias => $order) {
            $field = $this->getField($alias);
            $colArray = array();
            foreach ($array as $key => $row) {
                $colArray[$key] = isset($row[$field->getQualifier()]) ? $row[$field->getQualifier()] : null;
            }
            $sortArray[] = $colArray;
            $sortArray[] = ($order === OrderBy::ASC) ? SORT_ASC : SORT_DESC;
        }

        $sortArray[] = &$array;
        call_user_func_array('array_multisort', $sortArray);

        $this->isOrdered = true;
    }

    protected function applyFilters()
    {
        $this->copy = array();

        foreach ($this->source as $key => $item) {
            $keep = true;
            foreach ($this->filter as $filter) {
                if (!count($filter->getFields())) {
                    continue;
                }

                if (!$this->checkItem($item, $filter)) {
                    $keep = false;
                    break;
                }
            }

            if ($keep) {
                $this->copy[] = $item;
            }
        }
    }

    protected function checkItem($item, Filter $filter)
    {
        $criteria = array();
        foreach ($filter->getFields() as $key => $alias) {
            $field          = $this->getField($alias);

            $itemValue      = isset($item[$field->getQualifier()]) ? $item[$field->getQualifier()] : null;
            $filterOperator = $filter->getOperator($key);
            $filterValue    = $filter->getValue($key);

            if (Field::TYPE_DATETIME === $field->getType()) {
                $criteria[] = $this->checkDateTimeCondition($field, $itemValue, $filterOperator, $filterValue);
            }
            else {
                $criteria[] = $this->checkStringCondition($field, $itemValue, $filterOperator, $filterValue);
            }
        }

        foreach ($filter->getLogical() as $layer) {
            $criteriumIndex = 0;
            $concatCriteria = array();

            foreach ($layer as $log) {
                list($operator, $count) = $log;
                if (null === $count) {
                    $count = count($criteria) - $criteriumIndex;
                }

                // Criteria for the operator
                $subCriteria   = array();
                foreach (array_slice($criteria, $criteriumIndex, $count, true) as $criterium) {
                    if (null !== $criterium) {
                        $subCriteria[] = $criterium;
                    }
                }
                $criteriumIndex += $count;

                // Apply operator
                if (Filter::LOGICAL_OR === $operator) {
                    $concatCriteria[] = in_array(true, $subCriteria, true);
                }
                else {
                    $concatCriteria[] = !in_array(false, $subCriteria, true);
                }

                // Complete array
                for ($count--; $count; $count--) {
                    $concatCriteria[] = null;
                }
            }
            $criteria = $concatCriteria;
        }

        return current($criteria);
    }

    protected function checkDateTimeCondition(Field $field, $itemValue, $operator, $filterValue)
    {
        if (is_string($filterValue)
            && !strlen($filterValue)
            && !in_array($operator, array(Filter::OPERATOR_NULL, Filter::OPERATOR_NOT_NULL))
            ) {
            return true;
        }

        switch ($operator) {

            default:
            case Filter::OPERATOR_CONTAINS:
                $keep = preg_match('/'.preg_quote($filterValue, '/').'/i', $field->formatOutput($itemValue));
                break;

            case Filter::OPERATOR_NOT_CONTAINS:
                $keep = !preg_match('/'.preg_quote($filterValue, '/').'/i', $field->formatOutput($itemValue));
                break;

            case Filter::OPERATOR_EQUALS:
                // $keep = preg_match('/^'.preg_quote($filterValue, '/').'$/i', $field->formatOutput($itemValue));
                $filterValue = $field->formatInput($filterValue);
                if ($itemValue instanceOf \DateTime && $filterValue instanceOf \DateTime) {
                    $keep = ($itemValue == $filterValue);
                }
                else {
                    $keep = false;
                }
                break;

            case Filter::OPERATOR_NOT_EQUALS:
                // $keep = !preg_match('/^'.preg_quote($filterValue, '/').'$/i', $field->formatOutput($itemValue));
                $filterValue = $field->formatInput($filterValue);
                if ($itemValue instanceOf \DateTime && $filterValue instanceOf \DateTime) {
                    $keep = ($itemValue != $filterValue);
                }
                else {
                    $keep = false;
                }
                break;

            case Filter::OPERATOR_NULL:
                $keep = (is_null($itemValue) || $itemValue === false || $itemValue === '');
                break;

            case Filter::OPERATOR_NOT_NULL:
                $keep = !(is_null($itemValue) || $itemValue === false || $itemValue === '');
                break;

            case Filter::OPERATOR_GREATER:
                $filterValue = $field->formatInput($filterValue);
                if ($itemValue instanceOf \DateTime && $filterValue instanceOf \DateTime) {
                    $keep = ($itemValue > $filterValue);
                }
                else {
                    $keep = false;
                }
                break;

            case Filter::OPERATOR_GREATER_EQUALS:
                $filterValue = $field->formatInput($filterValue);
                if ($itemValue instanceOf \DateTime && $filterValue instanceOf \DateTime) {
                    $keep = ($itemValue >= $filterValue);
                }
                else {
                    $keep = false;
                }
                break;

            case Filter::OPERATOR_LESS:
                $filterValue = $field->formatInput($filterValue);
                if ($itemValue instanceOf \DateTime && $filterValue instanceOf \DateTime) {
                    $keep = ($itemValue < $filterValue);
                }
                else {
                    $keep = false;
                }
                break;

            case Filter::OPERATOR_LESS_EQUALS:
                $filterValue = $field->formatInput($filterValue);
                if ($itemValue instanceOf \DateTime && $filterValue instanceOf \DateTime) {
                    $keep = ($itemValue <= $filterValue);
                }
                else {
                    $keep = false;
                }
                break;

            case Filter::OPERATOR_IN:
                if (!is_array($filterValue)) {
                    $filterValue = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $filterValue, -1, PREG_SPLIT_NO_EMPTY);
                }
                $keep = in_array($field->formatOutput($itemValue), $filterValue);
                break;

            case Filter::OPERATOR_NOT_IN:
                if (!is_array($filterValue)) {
                    $filterValue = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $filterValue, -1, PREG_SPLIT_NO_EMPTY);
                }
                $keep = !in_array($field->formatOutput($itemValue), $filterValue);
                break;
        }

        return (bool) $keep;
    }

    protected function checkStringCondition(Field $field, $itemValue, $operator, $filterValue)
    {
        // Because of rounded value, we transform item value in output format
        if (Field::TYPE_NUMBER === $field->getType()) {
            $itemValue   = $field->formatOutput($itemValue);
        }
        else {
            $filterValue = $field->formatInput($filterValue);
        }

        if (is_string($filterValue)
            && !strlen($filterValue)
            && !in_array($operator, array(Filter::OPERATOR_NULL, Filter::OPERATOR_NOT_NULL))
            ) {
            return true;
        }

        switch ($operator) {

            default:
            case Filter::OPERATOR_CONTAINS:
                $keep = preg_match('/'.preg_quote($filterValue, '/').'/i', $itemValue);
                break;

            case Filter::OPERATOR_NOT_CONTAINS:
                $keep = !preg_match('/'.preg_quote($filterValue, '/').'/i', $itemValue);
                break;

            case Filter::OPERATOR_EQUALS:
                $keep = preg_match('/^'.preg_quote($filterValue, '/').'$/i', $itemValue);
                break;

            case Filter::OPERATOR_NOT_EQUALS:
                $keep = !preg_match('/^'.preg_quote($filterValue, '/').'$/i', $itemValue);
                break;

            case Filter::OPERATOR_NULL:
                $keep = (is_null($itemValue) || $itemValue === false || $itemValue === '');
                break;

            case Filter::OPERATOR_NOT_NULL:
                $keep = !(is_null($itemValue) || $itemValue === false || $itemValue === '');
                break;

            case Filter::OPERATOR_GREATER:
                $keep = ($itemValue > $filterValue);
                break;

            case Filter::OPERATOR_GREATER_EQUALS:
                $keep = ($itemValue >= $filterValue);
                break;

            case Filter::OPERATOR_LESS:
                $keep = ($itemValue < $filterValue);
                break;

            case Filter::OPERATOR_LESS_EQUALS:
                $keep = ($itemValue <= $filterValue);
                break;

            case Filter::OPERATOR_IN:
                if (!is_array($filterValue)) {
                    $filterValue = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $filterValue, -1, PREG_SPLIT_NO_EMPTY);
                }
                $keep = in_array($itemValue, $filterValue);
                break;

            case Filter::OPERATOR_NOT_IN:
                if (!is_array($filterValue)) {
                    $filterValue = preg_split("/((\r(?!\n))|((?<!\r)\n)|(\r\n))/", $filterValue, -1, PREG_SPLIT_NO_EMPTY);
                }
                $keep = !in_array($itemValue, $filterValue);
                break;
        }

        return (bool) $keep;
    }
}