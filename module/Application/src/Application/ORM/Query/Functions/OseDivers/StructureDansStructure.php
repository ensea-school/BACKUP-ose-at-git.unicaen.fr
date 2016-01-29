<?php

namespace Application\ORM\Query\Functions\OseDivers;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class StructureDansStructure extends FunctionNode
{
    public $structuretesteeId;
    public $structureCibleId;
    

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf('OSE_DIVERS.STRUCTURE_DANS_STRUCTURE(%s, %s)',
                $this->structuretesteeId->dispatch($sqlWalker),
                $this->structureCibleId->dispatch($sqlWalker));
    }
    
    /**
     * NB: le format DQL attendu est "OSE_DIVERS_STRUCTURE_DANS_STRUCTURE"
     * (et non pas "OSE_DIVERS.STRUCTURE_DANS_STRUCTURE").
     * 
     * @param \Doctrine\ORM\Query\Parser $parser
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->structuretesteeId = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->structureCibleId = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}