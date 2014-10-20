<?php

namespace Application\Rule\Intervenant;

use Application\Rule\Expr as BaseExpr;
use Application\Traits\IntervenantAwareTrait;
use Common\Exception\LogicException;

/**
 * Expression AND ou OR de règles métiers.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Expr extends BaseExpr
{
    use IntervenantAwareTrait;
    
    /**
     * Spécifie l'intervenant concerné.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        
        foreach ($this->rules as $rule) {
            $rule->setIntervenant($intervenant);
        }
        
        return $this;
    }
    
    /**
     * 
     * @return string
     * @throws LogicException Opérateur inattendu
     */
    public function getQuerySQL()
    {
        switch ($this->operator) {
            case self::OPERATOR_AND:
                $glue = 'INTERSECT';
                break;
            case self::OPERATOR_OR:
                $glue = 'UNION';
                break;
            default:
                throw new LogicException("Opérateur inattendu.");
        }
        
        $parts = [];
        foreach ($this->rules as $rule) {
            $parts[] = $rule->getQuerySQL();
        }
        
        return '(' . PHP_EOL . implode(PHP_EOL . $glue . PHP_EOL, $parts) . PHP_EOL . ')' . PHP_EOL;
    }
    
    /**
     * Retourne les paramètres nécessaires à la requête de cette règle.
     * 
     * @return array
     */
    public function getQueryParameters()
    {
        $parameters = [];
        
        foreach ($this->rules as $rule) {
            $parameters = array_merge($parameters, $rule->getQueryParameters());
        }
        
        return $parameters;
    }
}