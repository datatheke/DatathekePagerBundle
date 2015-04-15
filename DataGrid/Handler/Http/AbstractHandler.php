<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Handler\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\HttpDatagridInterface;

abstract class AbstractHandler implements HttpHandlerInterface
{
    protected $options;

    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'method'        => 'query',
            'jsonp_padding' => 'callback',
            )
        );
    }

    public function setMethod($method)
    {
        $this->options['method'] = $method;

        return $this;
    }

    public function setJsonPPadding($jsonPPadding)
    {
        $this->options['jsonp_padding'] = $jsonPPadding;

        return $this;
    }

    abstract public function handleRequest(HttpDatagridInterface $datagrid, Request $request);

    protected function createJsonResponse(Request $request, $content)
    {
        $response = new JsonResponse($content);

        if ($this->has($request, $this->options['jsonp_padding'])) {
            $response->setCallback($this->get($request, $this->options['jsonp_padding']));
        } else {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }

    protected function has(Request $request, $param)
    {
        if ('request' === $this->options['method']) {
            return $request->request->has($param);
        }

        return $request->query->has($param);
    }

    protected function get(Request $request, $param, $default = null, $deep = false)
    {
        if ('request' === $this->options['method']) {
            return $request->request->get($param, $default, $deep);
        }

        return $request->query->get($param, $default, $deep);
    }

    protected function getItems(HttpDatagridInterface $datagrid, $forceArray = false)
    {
        $items = array();
        foreach ($datagrid->getPager()->getItems() as $row) {
            $item = array();
            foreach ($datagrid->getColumns() as $alias => $column) {
                if ($forceArray) {
                    $item[] = $datagrid->getColumnValue($column, $row);
                } else {
                    $item[$alias] = $datagrid->getColumnValue($column, $row);
                }
            }
            $items[] = $item;
        }

        return $items;
    }
}
