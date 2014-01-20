<?php

namespace Datatheke\Bundle\PagerBundle\Twig\TokenParser;

use Datatheke\Bundle\PagerBundle\Twig\Node\DataGridThemeNode;

class DataGridThemeTokenParser extends \Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     *
     * @return \Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $datagrid = $this->parser->getExpressionParser()->parseExpression();
        $resources = array();
        do {
            $resources[] = $this->parser->getExpressionParser()->parseExpression();
        } while (!$stream->test(\Twig_Token::BLOCK_END_TYPE));

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new DataGridThemeNode($datagrid, new \Twig_Node($resources), $lineno, $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'datagrid_theme';
    }
}
