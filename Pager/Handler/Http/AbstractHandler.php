<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\Field;

abstract class AbstractHandler implements HttpHandlerInterface
{
    protected $options;

    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'query',
            )
        );
    }

    public function setMethod($method)
    {
        $this->options['method'] = $method;

        return $this;
    }

    abstract public function handleRequest(PagerInterface $pager, Request $request);

    protected function has(Request $request, $param)
    {
        if ('request' === $this->options['method']) {
            return $request->request->has($param);
        }

        return $request->query->has($param);
    }

    protected function get(Request $request, $param, $default = null)
    {
        if ('request' === $this->options['method']) {
            return $request->request->get($param, $default);
        }

        return $request->query->get($param, $default);
    }

    protected function search(PagerInterface $pager, $query, array $fields = null)
    {
        $filter = array('operator' => Filter::LOGICAL_OR, 'criteria' => array());
        foreach ($pager->getFields() as $alias => $field) {
            if (is_array($fields) && !in_array($alias, $fields)) {
                continue;
            }

            if (in_array($field->getType(), array(Field::TYPE_BOOLEAN, Field::TYPE_OBJECT, Field::TYPE_NUMBER)) && !is_numeric($query)) {
                continue;
            }

            $operator = Field::TYPE_STRING === $field->getType() ? Filter::OPERATOR_CONTAINS : Filter::OPERATOR_EQUALS;

            $filter['criteria'][] = array(
                'field' => $alias,
                'operator' => $operator,
                'value' => $query,
                );
        }

        $pager->setFilter(Filter::createFromArray($filter), 'handler');
    }
}
