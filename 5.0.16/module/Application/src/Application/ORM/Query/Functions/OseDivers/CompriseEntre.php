<?php

namespace Application\ORM\Query\Functions\OseDivers;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class CompriseEntre extends FunctionNode
{
    public $histoCreation;
    public $histoDestruction;
    public $dateObs;


    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $sql = sprintf('OSE_DIVERS.COMPRISE_ENTRE(%s, %s',
                $this->histoCreation->dispatch($sqlWalker),
                $this->histoDestruction->dispatch($sqlWalker));
        
        if ($this->dateObs){
            $sql .= ','.$this->dateObs->dispatch($sqlWalker);
        }
        $sql .= ')';
        return $sql;
    }

    /**
     * NB: le format DQL attendu est "compriseEntre"
     * (et non pas "OSE_DIVERS.COMPRISE_ENTRE").
     * 
     * @param \Doctrine\ORM\Query\Parser $parser
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $lexer = $parser->getLexer();
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->histoCreation = $parser->ArithmeticPrimary();
        if(Lexer::T_COMMA === $lexer->lookahead['type']){
            $parser->match(Lexer::T_COMMA);
            $this->histoDestruction = $parser->ArithmeticPrimary();
        }
        if(Lexer::T_COMMA === $lexer->lookahead['type']){
            $parser->match(Lexer::T_COMMA);
            $this->dateObs = $parser->ArithmeticPrimary();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}