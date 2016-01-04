<?php

namespace Datatheke\Bundle\PagerBundle\Twig;

use Datatheke\Bundle\PagerBundle\DataGrid\Column\Action\ActionInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Column\ColumnInterface;
use Datatheke\Bundle\PagerBundle\DataGrid\Configuration;
use Datatheke\Bundle\PagerBundle\DataGrid\DataGridViewInterface;
use Datatheke\Bundle\PagerBundle\Twig\TokenParser\DataGridThemeTokenParser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DataGridExtension extends \Twig_Extension
{
    protected $urlGenerator;
    protected $config;

    protected $themes;

    public function __construct(UrlGeneratorInterface $urlGenerator, Configuration $config)
    {
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;

        $this->themes = new \SplObjectStorage();
    }

    public function getName()
    {
        return 'DatathekeDataGridExtension';
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
            new \Twig_SimpleFunction('datagrid', array($this, 'renderDataGrid'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_javascripts', array($this, 'renderJavascripts'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_stylesheets', array($this, 'renderStyleSheets'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_content', array($this, 'renderContent'), array('is_safe' => array('html'), 'needs_environment' => true)),

            new \Twig_SimpleFunction('datagrid_header', array($this, 'renderHeader'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_body', array($this, 'renderBody'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_footer', array($this, 'renderFooter'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_paginate', array($this, 'renderPaginate'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_items_per_page', array($this, 'renderItemsPerPage'), array('is_safe' => array('html'), 'needs_environment' => true)),

            new \Twig_SimpleFunction('datagrid_row_order_by', array($this, 'renderRowOrderBy'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_row_filters', array($this, 'renderRowFilters'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_row_items', array($this, 'renderRowItems'), array('is_safe' => array('html'), 'needs_environment' => true)),

            new \Twig_SimpleFunction('datagrid_column_order_by', array($this, 'renderColumnOrderBy'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_column_filter', array($this, 'renderColumnFilter'), array('is_safe' => array('html'), 'needs_environment' => true)),
            new \Twig_SimpleFunction('datagrid_column_item', array($this, 'renderColumnItem'), array('is_safe' => array('html'), 'needs_environment' => true)),

            new \Twig_SimpleFunction('datagrid_item', array($this, 'renderItem'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('datagrid_action', array($this, 'renderAction'), array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    public function setTheme($datagrid, array $resources)
    {
        $this->themes->attach($datagrid, $resources);
    }

    protected function render(\Twig_Environment $env, $datagrid, $blocks, $params = array())
    {
        if (!is_array($blocks)) {
            $blocks = array($blocks);
        }

        $templates = array($this->config->getTheme());
        if (isset($this->themes[$datagrid])) {
            $templates = array_merge($this->themes[$datagrid], $templates);
        }

        $context = $env->mergeGlobals(array_merge($params, array('datagrid' => $datagrid)));
        foreach ($templates as $template) {
            if (!$template instanceof \Twig_Template) {
                $template = $env->loadTemplate($template);
            }

            foreach ($blocks as $block) {
                if ($template->hasBlock($block)) {
                    return $template->renderBlock($block, $context);
                } else {
                    $parent = $template->getParent(array());
                    if (false !== $parent && $parent->hasBlock($block)) {
                        return $parent->renderBlock($block, $context);
                    }
                }
            }
        }

        throw new \Exception('Block '.$block.' not found');
    }

    public function renderDataGrid(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid', $params);
    }

    public function renderJavascripts(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_javascripts', $params);
    }

    public function renderStyleSheets(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_stylesheets', $params);
    }

    public function renderContent(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_content', $params);
    }

    public function renderHeader(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_header', $params);
    }

    public function renderBody(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_body', $params);
    }

    public function renderFooter(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_footer', $params);
    }

    public function renderPaginate(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_paginate', $params);
    }

    public function renderItemsPerPage(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_items_per_page', $params);
    }

    public function renderRowOrderBy(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_row_order_by', $params);
    }

    public function renderRowFilters(\Twig_Environment $env, DataGridViewInterface $datagrid, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_row_filters', $params);
    }

    public function renderRowItems(\Twig_Environment $env, DataGridViewInterface $datagrid, $item, array $params = array())
    {
        return $this->render($env, $datagrid, 'datagrid_row_items', array_merge($params, array('item' => $item)));
    }

    public function renderColumnOrderBy(\Twig_Environment $env, DataGridViewInterface $datagrid, ColumnInterface $column, $alias, array $params = array())
    {
        $blocks = array(
            'datagrid_column_order_by__'.$this->sanitizeAlias($alias),
            'datagrid_column_order_by_'.$column->getType(),
            'datagrid_column_order_by',
        );

        return $this->render($env, $datagrid, $blocks, array_merge($params, array('column' => $column, 'alias' => $alias)));
    }

    public function renderColumnFilter(\Twig_Environment $env, DataGridViewInterface $datagrid, ColumnInterface $column, $alias, array $params = array())
    {
        $blocks = array(
            'datagrid_column_filter__'.$this->sanitizeAlias($alias),
            'datagrid_column_filter_'.$column->getType(),
            'datagrid_column_filter',
        );

        return $this->render($env, $datagrid, $blocks, array_merge($params, array('column' => $column, 'alias' => $alias)));
    }

    public function renderColumnItem(\Twig_Environment $env, DataGridViewInterface $datagrid, ColumnInterface $column, $item, $alias, array $params = array())
    {
        $blocks = array(
            'datagrid_column_item__'.$this->sanitizeAlias($alias),
            'datagrid_column_item_'.$column->getType(),
            'datagrid_column_item',
        );

        return $this->render($env, $datagrid, $blocks, array_merge($params, array('item' => $item, 'column' => $column, 'alias' => $alias)));
    }

    public function renderItem(DataGridViewInterface $datagrid, ColumnInterface $column, $item)
    {
        return $datagrid->getColumnValue($column, $item);
    }

    public function renderAction(\Twig_Environment $env, DataGridViewInterface $datagrid, ActionInterface $action, $alias, $item = null, array $params = array())
    {
        if (!$action->evaluateDisplay($datagrid, $item)) {
            return '';
        }

        $blocks = array(
            'datagrid_action__'.$this->sanitizeAlias($alias),
            'datagrid_action_'.$action->getType(),
            'datagrid_action',
        );

        return $this->render($env, $datagrid, $blocks, array_merge($params, array('action' => $action, 'alias' => $alias, 'item' => $item)));
    }

    protected function sanitizeAlias($alias)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $alias);
    }
}
