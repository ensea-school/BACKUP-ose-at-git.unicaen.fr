<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Entity\Db\Interfaces\IntervenantAwareInterface;
use Application\Service\Intervenant as IntervenantService;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of AbstractIntervenantRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractIntervenantRule extends AbstractRule implements IntervenantAwareInterface
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
     * Retourne la requête SQL de cette règle.
     * 
     * ATTENTION: il ne doit pas y avoir de paramètres non valués de la forme ":param"!!
     * 
     * @return string
     */
    public function getQuerySQL()
    {
        return $this->getQueryBuilder()->getQuery()->getSQL();
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