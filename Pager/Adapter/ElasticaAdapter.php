<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Adapter;

use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\Field;
use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\SearchableInterface;

class ElasticaAdapter implements AdapterInterface
{
    protected $searchable;
    protected $fields;

    protected $count;
    protected $query;

    public function __construct(SearchableInterface $searchable, array $fields = null, AbstractQuery $query = null)
    {
        $this->searchable = $searchable;
        $this->query = $query ?: new Query();

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
        // @TODO
        $this->fields = array();
    }

    public function count()
    {
        if (null === $this->count) {
            $this->count = $this->searchable->count($this->query);
        }

        return $this->count;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getItems($offset = 0, $itemCountPerPage = null)
    {
        $this->query->setFrom($offset)->setSize($itemCountPerPage);

        return $this->searchable->search($this->query);
    }

    public function setFilter(Filter $filter = null, $name = 'default')
    {
        $this->count = null;
        $query = new Query\Bool();

        foreach ($filter->getFields() as $key => $alias) {
            $field      = $this->fields[$alias];
            $operator = $filter->getOperator($key);
            $value = $filter->getValue($key);

            if ('' === $value) {
                continue;
            }

            switch ($operator) {
                case Filter::OPERATOR_EQUALS:
                    $query->addMust(new Query\Term(array(
                        $field->getQualifier() => $value,
                    )));
                    break;

                default;
                    $query->addMust(new Query\Wildcard($field->getQualifier(), '*'.$value.'*'));
                    break;
            }
        }

        $this->query->setQuery($query);

        return $this;
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        if (null === $orderBy) {
            $this->query->setSort(array());
        } else {
            $this->query->setSort((array) $orderBy);
        }

        return $this;
    }
}
