<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

class Filter
{
    const OPERATOR_EQUALS         = '=';
    const OPERATOR_NOT_EQUALS     = '!=';
    const OPERATOR_CONTAINS       = '~';
    const OPERATOR_NOT_CONTAINS   = '!~';
    const OPERATOR_NULL           = 'X';
    const OPERATOR_NOT_NULL       = '!X';
    const OPERATOR_GREATER        = '>';
    const OPERATOR_GREATER_EQUALS = '>=';
    const OPERATOR_LESS           = '<';
    const OPERATOR_LESS_EQUALS    = '<=';
    const OPERATOR_IN             = '[]';
    const OPERATOR_NOT_IN         = '![]';

    const LOGICAL_AND             = '&';
    const LOGICAL_OR              = '|';

    protected $fields;
    protected $values;
    protected $operators;
    protected $logical;

    public function __construct(array $fields = array(), array $values = array(), array $operators = array(), array $logical = array())
    {
        $this->fields    = $fields;
        $this->values    = $values;
        $this->operators = $operators;
        $this->logical   = $logical;
    }

    public function toArray()
    {
        return array($this->fields, $this->values, $this->operators, $this->logical);
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getField($key)
    {
        if (!isset($this->fields[$key])) {
            return null;
        }

        return $this->fields[$key];
    }

    public function getValue($key)
    {
        if (!isset($this->values[$key])) {
            return null;
        }

        return $this->values[$key];
    }

    public function getOperator($key)
    {
        if (!isset($this->operators[$key])) {
            return self::OPERATOR_CONTAINS;
        }

        return $this->operators[$key];
    }

    public function getLogical()
    {
        if (empty($this->logical)) {
            return array(array(self::LOGICAL_AND => null));
        }

        return $this->logical;
    }
}