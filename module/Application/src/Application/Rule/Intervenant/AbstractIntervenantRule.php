<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Service\Intervenant as IntervenantService;
use Application\Traits\IntervenantAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of AbstractIntervenantRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractIntervenantRule extends AbstractRule
{
    use IntervenantAwareTrait;
    
    /**
     * Reformatte en extrayant chaque 'id' pour l'utiliser comme clé. 
     * 
     * @param array $result Tableau au format [clé => ['id' => entier]]
     * @return array Tableau au format [entier => ['id' => entier]]
     */
    static public function normalizeResult($result)
    {
        $idExtractor = function($value) {
            if (!is_array($value)) {
                return $value;
            }
            if (isset($value['id'])) {
                return $value['id'];
            }
            if (isset($value['ID'])) {
                return $value['ID'];
            }
            return reset($value);
        };
        
        $new = [];
        foreach ($result as $value) {
            $id = $idExtractor($value);
            $new[$id] = ['id' => $id];
        }
        
        return $new;
    }
    
    /**
     * @var string
     */
    protected $querySQL;
    
    /**
     * Retourne la requête SQL de cette règle.
     * NB: les paramètres éventuels ne sont pas valués et restent sous la forme ":param".
     * 
     * @return string
     */
    public function getQuerySQL()
    {
        if (null === $this->querySQL) {
            $this->querySQL = $this->getQueryBuilder()->getQuery()->getSQL();
        }
        
        return $this->querySQL;
    }
    
    /**
     * Retourne le query builder de cette règle.
     * 
     * @return QueryBuilder
     */
    abstract public function getQueryBuilder();
    
    /**
     * @return IntervenantService
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
}