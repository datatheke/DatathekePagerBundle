<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Datatheke\Bundle\PagerBundle\Datagrid\Column\ColumnInterface;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;

class DataGrid implements DataGridInterface
{
    protected $accessor;
    protected $pager;
    protected $columns;
    protected $options;

    protected $initialized;

    public function __construct(PagerInterface $pager, array $columns, array $options = array())
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->pager = $pager;
        $this->columns = $columns;

        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        $this->initialized = false;
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
    }

    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    public function getOption($option)
    {
        if (!$this->hasOption($option)) {
            throw new \InvalidArgumentException(sprintf('The "%s" option does not exist.', $option));
        }

        return $this->options[$option];
    }

    public function addColumn(ColumnInterface $column, $alias = null)
    {
        if (null !== $alias) {
            $this->columns[$alias] = $column;
        } else {
            $this->columns[] = $column;
        }

        return $this;
    }

    public function getColumns()
    {
        $columns = array();
        foreach ($this->columns as $alias => $column) {
            if ($column->isVisible()) {
                $columns[$alias] = $column;
            }
        }

        return $columns;
    }

    public function getColumn($alias)
    {
        if (!isset($this->columns[$alias])) {
            throw new \InvalidArgumentException(sprintf('The "%s" column does not exist.', $alias));
        }

        return $this->columns[$alias];
    }

    public function sortColumns(array $order)
    {
        $sorted = array();
        foreach ($order as $alias) {
            $sorted[$alias] = $this->getColumn($alias);
        }

        foreach ($this->columns as $alias => $column) {
            if (!isset($sorted[$alias])) {
                $sorted[$alias] = $column;
            }
        }
        $this->columns = $sorted;

        return $this;
    }

    public function showOnly(array $columns)
    {
        foreach ($this->columns as $alias => $column) {
            if (in_array($alias, $columns)) {
                $column->show();
            } else {
                $column->hide();
            }
        }

        return $this;
    }

    public function getPager()
    {
        return $this->pager;
    }

    public function getColumnValue(ColumnInterface $column, $item)
    {
        if (null === $column->getField()) {
            return;
        }

        try {
            $value = $this->accessor->getValue($item, $column->getField()->getPropertyPath());
        } catch (NoSuchPropertyException $e) {
            return;
        }

        return $column->format($value);
    }

    protected function initialize()
    {
        if (true === $this->initialized) {
            return;
        }

        foreach ($this->columns as $column) {
            $column->initialize();
        }

        $this->initialized = true;
    }
}
