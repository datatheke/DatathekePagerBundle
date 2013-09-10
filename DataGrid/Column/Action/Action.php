<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column\Action;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\DataGrid\Datagrid;

class Action implements ActionInterface
{
    protected $label;
    protected $route;
    protected $options;

    public function __construct($label, $route, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        $this->label      = $label;
        $this->route      = $route;
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'parameters'           => array(),
            'map_item'             => true,
            'item_mapping'         => array('id' => 'id'),
            'ask_confirmation'     => false,
            'confirmation_message' => null,
            'icon'                 => null,
            'evaluate_callback'    => null
            )
        );
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

    public function getLabel()
    {
        return $this->label;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getParameters(Datagrid $datagrid, $item)
    {
        $parameters = array();
        if ($this->options['map_item']) {
            foreach ($this->options['item_mapping'] as $param => $column) {
                $parameters[$param] = $datagrid->getColumnValue($datagrid->getColumn($column), $item);
            }
        }

        return array_merge($parameters, $this->options['parameters']);
    }

    public function evaluateDisplay(Datagrid $datagrid, $item)
    {
        if (null === ($callback = $this->options['evaluate_callback'])) {
            return true;
        }

        return $callback($datagrid, $item);
    }

    public function getType()
    {
        return 'default';
    }
}
