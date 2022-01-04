<?php

namespace App\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class RoundFunction extends FunctionNode
{
    /**
     * simpleArithmeticExpression
     *
     * @var mixed
     * @access public
     */
    public $simpleArithmeticExpression;
    /**
     * roundPrecision
     *
     * @var mixed
     * @access public
     */
    public $roundPrecision;


    /**
     * getSql
     *
     * @param SqlWalker $sqlWalker
     * @access public
     * @return string
     */
    public function getSql (SqlWalker $sqlWalker)
    {
        return 'ROUND(' .
            $sqlWalker->walkSimpleArithmeticExpression($this->simpleArithmeticExpression) . ',' .
            $sqlWalker->walkStringPrimary($this->roundPrecision) .
            ')';
    }


    /**
     * parse
     *
     * @param Parser $parser
     * @access public
     * @return void
     */
    public function parse (Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->simpleArithmeticExpression = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->roundPrecision = $parser->ArithmeticExpression();
        if ($this->roundPrecision == null)
        {
            $this->roundPrecision = 0;
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}