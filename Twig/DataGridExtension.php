<?php

namespace Datatheke\Bundle\PagerBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Datatheke\Bundle\PagerBundle\Twig\TokenParser\DataGridThemeTokenParser;
use Datatheke\Bundle\PagerBundle\DataGrid\WebDataGrid;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\Action\ActionInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\ColumnInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Configuration;

class DataGridExtension extends \Twig_Extension
{
    protected $environment;
    protected $urlGenerator;
    protected $config;

    protected $themes;

    public function __construct(UrlGeneratorInterface $urlGenerator, Configuration $config)
    {
        $this->urlGenerator = $urlGenerator;
        $this->config       = $config;

        $this->themes       = new \SplObjectStorage();
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getName()
    {
        return 'DatahtekeDataGridExtension';
    }

    public function getTokenParsers()
    {
        return array(
            new DataGridThemeTokenParser(),
        );
    }

    public function getFunctions()
    {
        return array(
            'datagrid'                 => new \Twig_Function_Method($this, 'renderDataGrid', array('is_safe' => array('html'))),
            'datagrid_javascripts'     => new \Twig_Function_Method($this, 'renderJavascripts', array('is_safe' => array('html'))),
            'datagrid_stylesheets'     => new \Twig_Function_Method($this, 'renderStyleSheets', array('is_safe' => array('html'))),
            'datagrid_content'         => new \Twig_Function_Method($this, 'renderContent', array('is_safe' => array('html'))),

            'datagrid_header'          => new \Twig_Function_Method($this, 'renderHeader', array('is_safe' => array('html'))),
            'datagrid_body'            => new \Twig_Function_Method($this, 'renderBody', array('is_safe' => array('html'))),
            'datagrid_footer'          => new \Twig_Function_Method($this, 'renderFooter', array('is_safe' => array('html'))),
            'datagrid_paginate'        => new \Twig_Function_Method($this, 'renderPaginate', array('is_safe' => array('html'))),
            'datagrid_items_per_page'  => new \Twig_Function_Method($this, 'renderItemsPerPage', array('is_safe' => array('html'))),

            'datagrid_row_order_by'    => new \Twig_Function_Method($this, 'renderRowOrderBy', array('is_safe' => array('html'))),
            'datagrid_row_filters'     => new \Twig_Function_Method($this, 'renderRowFilters', array('is_safe' => array('html'))),
            'datagrid_row_items'       => new \Twig_Function_Method($this, 'renderRowItems', array('is_safe' => array('html'))),

            'datagrid_column_order_by' => new \Twig_Function_Method($this, 'renderColumnOrderBy', array('is_safe' => array('html'))),
            'datagrid_column_filter'   => new \Twig_Function_Method($this, 'renderColumnFilter', array('is_safe' => array('html'))),
            'datagrid_column_item'     => new \Twig_Function_Method($this, 'renderColumnItem', array('is_safe' => array('html'))),

            'datagrid_item'            => new \Twig_Function_Method($this, 'renderItem', array('is_safe' => array('html'))),
            'datagrid_action'          => new \Twig_Function_Method($this, 'renderAction', array('is_safe' => array('html'))),
        );
    }

    public function setTheme($datagrid, array $resources)
    {
        $this->themes->attach($datagrid, $resources);
    }

    protected function render($datagrid, $blocks, $params = array())
    {
        if (!is_array($blocks)) {
            $blocks = array($blocks);
        }

        $templates = array($this->config->getTheme());
        if (isset($this->themes[$datagrid])) {
            $templates = array_merge($this->themes[$datagrid], $templates);
        }

        $context = array_merge($params, array('datagrid' => $datagrid));
        foreach ($templates as $template) {
            if (!$template instanceof \Twig_Template) {
                $template = $this->environment->loadTemplate($template);
            }

            foreach ($blocks as $block) {
                if ($template->hasBlock($block)) {
                    return $template->renderBlock($block, $context);
                }
                else {
                    $parent = $template->getParent(array());
                    if (false !== $parent && $parent->hasBlock($block)) {
                        return $parent->renderBlock($block, $context);
                    }
                }
            }
        }

        throw new \Exception('Block '.$block.' not found');
    }

    public function renderDataGrid(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid', $params);
    }

    public function renderJavascripts(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_javascripts', $params);
    }

    public function renderStyleSheets(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_stylesheets', $params);
    }

    public function renderContent(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_content', $params);
    }

    public function renderHeader(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_header', $params);
    }

    public function renderBody(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_body', $params);
    }

    public function renderFooter(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_footer', $params);
    }

    public function renderPaginate(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_paginate', $params);
    }

    public function renderItemsPerPage(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_items_per_page', $params);
    }

    public function renderRowOrderBy(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_row_order_by', $params);
    }

    public function renderRowFilters(WebDataGrid $datagrid, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_row_filters', $params);
    }

    public function renderRowItems(WebDataGrid $datagrid, $item, array $params = array())
    {
        return $this->render($datagrid, 'datagrid_row_items', array_merge($params, array('item' => $item)));
    }

    public function renderColumnOrderBy(WebDataGrid $datagrid, ColumnInterface $column, $alias, array $params = array())
    {
        $blocks = array(
            'datagrid_column_order_by__'.$this->sanitizeAlias($alias),
            'datagrid_column_order_by_'.$column->getType(),
            'datagrid_column_order_by'
        );
        return $this->render($datagrid, $blocks, array_merge($params, array('column' => $column, 'alias' => $alias)));
    }

    public function renderColumnFilter(WebDataGrid $datagrid, ColumnInterface $column, $alias, array $params = array())
    {
        $blocks = array(
            'datagrid_column_filter__'.$this->sanitizeAlias($alias),
            'datagrid_column_filter_'.$column->getType(),
            'datagrid_column_filter'
        );
        return $this->render($datagrid, $blocks, array_merge($params, array('column' => $column, 'alias' => $alias)));
    }

    public function renderColumnItem(WebDataGrid $datagrid, ColumnInterface $column, $item, $alias, array $params = array())
    {
        $blocks = array(
            'datagrid_column_item__'.$this->sanitizeAlias($alias),
            'datagrid_column_item_'.$column->getType(),
            'datagrid_column_item'
        );
        return $this->render($datagrid, $blocks, array_merge($params, array('item' => $item, 'column' => $column, 'alias' => $alias)));
    }

    public function renderItem(WebDataGrid $datagrid, ColumnInterface $column, $item)
    {
        return $datagrid->getColumnValue($column, $item);
    }

    public function renderAction(WebDataGrid $datagrid, ActionInterface $action, $alias, $item = null, array $params = array())
    {
        if (!$action->evaluateDisplay($datagrid, $item)) {
            return '';
        }

        $blocks = array(
            'datagrid_action__'.$this->sanitizeAlias($alias),
            'datagrid_action_'.$action->getType(),
            'datagrid_action'
        );
        return $this->render($datagrid, $blocks, array_merge($params, array('action' => $action, 'alias' => $alias, 'item' => $item)));
    }

    protected function sanitizeAlias($alias)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $alias);
    }
}
