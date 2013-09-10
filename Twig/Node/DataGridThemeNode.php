<?php

namespace Datatheke\Bundle\PagerBundle\Twig\Node;

class DataGridThemeNode extends \Twig_Node
{
    public function __construct(\Twig_NodeInterface $datagrid, \Twig_NodeInterface $resources, $lineno, $tag = null)
    {
        parent::__construct(array('datagrid' => $datagrid, 'resources' => $resources), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('echo $this->env->getExtension(\'DataGridExtension\')->setTheme(')
            ->subcompile($this->getNode('datagrid'))
            ->raw(', array(')
        ;

        foreach ($this->getNode('resources') as $resource) {
            $compiler
                ->subcompile($resource)
                ->raw(', ')
            ;
        }

        $compiler->raw("));\n");
    }
}
