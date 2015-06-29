<?php

namespace Common\ORM\Query\Functions\OseDivers;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Fonction DQL maison "pasHistorise()".
 * 
 * NB: le format DQL attendu est "pasHistorise(EntityExpression)". 
 * Exemples :
 * <pre>
 * $qb->andWhere("1 = pasHistorise(sr)")
 * $qb->andWhere("1 = pasHistorise(int.serviceReferentiel)")
 * </pre>
 * 
 * Fait appel à la fonction Oracle OSE_DIVERS.COMPRISE_ENTRE().
 */
class PasHistorise extends FunctionNode
{
    public $alias;
    public $dateObservation;

    /**
     * Parsing.
     * 
     * NB: le format DQL attendu est "pasHistorise(EntityExpression)".
     * Exemple :
     *  pasHistorise(s)
     *  pasHistorise(int.serviceReferentiel)
     * 
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $lexer = $parser->getLexer();
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->alias = $parser->EntityExpression();
        if(Lexer::T_COMMA === $lexer->lookahead['type']){
            $parser->match(Lexer::T_COMMA);
            $this->dateObservation = $parser->ArithmeticPrimary();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * Génère le code SQL.
     * 
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $expr1 = clone($this->alias);
        $expr2 = clone($this->alias);
        
        $expr1->field = 'histoCreation';
        $expr2->field = 'histoDestruction';
        
        $sql = sprintf('OSE_DIVERS.COMPRISE_ENTRE(%s, %s, %s)',
                $expr1->dispatch($sqlWalker),
                $expr2->dispatch($sqlWalker),
                $this->dateObservation ? $this->dateObservation->dispatch($sqlWalker) : 'null');
        
        return $sql;
    }
}