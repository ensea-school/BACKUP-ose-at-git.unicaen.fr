<?php

namespace Common\ORM\Query\Functions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class Replace extends FunctionNode
{
    public $inputString;
    public $searchString;
    public $replacementString;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf('REPLACE(%s, %s, %s)', 
                $this->inputString->dispatch($sqlWalker), 
                $this->searchString->dispatch($sqlWalker), 
                $this->replacementString->dispatch($sqlWalker));
    }
    
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->inputString = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->searchString = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->replacementString = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}