<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

use Datatheke\Bundle\PagerBundle\Pager\Field;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Action\ActionInterface;

abstract class AbstractColumn implements ColumnInterface
{
    protected $label;
    protected $field;

    protected $filterable = true;
    protected $sortable = true;
    protected $visible = true;
    protected $actions = array();

    public function __construct(Field $field = null, $label = null)
    {
        if (null === $label && null !== $field) {
            $label = $this->prettifyLabel($field->getPropertyPath());
        }

        $this->field = $field;
        $this->label = $label;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setField(Field $field)
    {
        $this->field = $field;

        return $this;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setFilterable($flag)
    {
        $this->filterable = $flag;

        return $this;
    }

    public function isFilterable()
    {
        return $this->filterable;
    }

    public function setSortable($flag)
    {
        $this->sortable = $flag;

        return $this;
    }

    public function isSortable()
    {
        return $this->sortable;
    }

    public function show()
    {
        $this->visible = true;

        return $this;
    }

    public function hide()
    {
        $this->visible = false;

        return $this;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    public function addAction(ActionInterface $action, $alias = null)
    {
        if (null !== $alias) {
            $this->actions[$alias] = $action;
        } else {
            $this->actions[] = $action;
        }

        return $this;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function format($value)
    {
        return $this->field->formatOutput($value);
    }

    public function initialize()
    {
    }

    protected function prettifyLabel($label)
    {
        return ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $label));
    }
}
