<?php

namespace Application\ORM\Query\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * ContainsFunction ::= "CONTAINS" "(" StringPrimary, StringPrimary, ArithmeticPrimary ")"
 */
class ContainsFunction extends FunctionNode
{
    public $firstStringPrimary;
    public $secondStringPrimary;
    public $arithmeticPrimary;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstStringPrimary = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondStringPrimary = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->arithmeticPrimary = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'CONTAINS(' .
            $this->firstStringPrimary->dispatch($sqlWalker) . ',' .
            $this->secondStringPrimary->dispatch($sqlWalker) . ',' .
            $this->arithmeticPrimary->dispatch($sqlWalker) .     
        ')';
    }
}