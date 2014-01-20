<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;

class Pager implements PagerInterface
{
    protected $adapter;
    protected $paginator;
    protected $options;

    protected $orderBy;
    protected $filter;

    public function __construct(AdapterInterface $adapter, array $options = array())
    {
        $this->adapter = $adapter;
        $this->orderBy = new OrderBy();
        $this->filter  = new Filter();

        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        $this->paginator = new Paginator($this->options['item_count_per_page'], $this->options['current_page_number']);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'item_count_per_page',
            )
        );

        $resolver->setDefaults(array(
            'current_page_number'         => 1,
            'item_count_per_page_choices' => array(),
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

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getFields()
    {
        return $this->adapter->getFields();
    }

    public function setCurrentPageNumber($currentPageNumber)
    {
        $this->paginator->setCurrentPageNumber($currentPageNumber);

        return $this;
    }

    public function getCurrentPageNumber()
    {
        return $this->getPaginator()->getCurrentPageNumber();
    }

    public function getItemCountPerPageChoices()
    {
        return $this->options['item_count_per_page_choices'];
    }

    public function setItemCountPerPage($itemCountPerPage)
    {
        $this->paginator->setItemCountPerPage($itemCountPerPage);

        return $this;
    }

    public function getItemCountPerPage()
    {
        return $this->getPaginator()->getItemCountPerPage();
    }

    public function setOrderBy(OrderBy $orderBy = null)
    {
        $this->orderBy = $orderBy;
        $this->adapter->setOrderBy($orderBy);

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setFilter(Filter $filter = null)
    {
        $this->filter = $filter;
        $this->adapter->setFilter($filter, 'pager');

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getItems()
    {
        return $this->adapter->getItems($this->getPaginator()->getItemOffset(), $this->getPaginator()->getItemCountPerPage());
    }

    public function getPageCount()
    {
        return $this->getPaginator()->getPageCount();
    }

    public function getTotalItemCount()
    {
        return $this->getPaginator()->getTotalItemCount();
    }

    public function getFirstItemNumber()
    {
        return $this->getPaginator()->getFirstItemNumber();
    }

    public function getLastItemNumber()
    {
        return $this->getPaginator()->getLastItemNumber();
    }

    public function getPreviousPageNumber()
    {
        return $this->getPaginator()->getPreviousPageNumber();
    }

    public function getNextPageNumber()
    {
        return $this->getPaginator()->getNextPageNumber();
    }

    public function getCurrentItemCount()
    {
        return $this->getPaginator()->getCurrentItemCount();
    }

    protected function getPaginator()
    {
        // Count items only when we really need the paginator
        $this->paginator->setTotalItemCount($this->adapter->count());

        return $this->paginator;
    }
}
