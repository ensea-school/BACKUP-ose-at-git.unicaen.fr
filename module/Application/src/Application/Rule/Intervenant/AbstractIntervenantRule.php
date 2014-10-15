<?php

namespace Application\Rule\Intervenant;

use Application\Traits\IntervenantAwareTrait;
use Application\Rule\AbstractRule;
use Application\Service\Intervenant as IntervenantService;

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
    protected function normalizeResult($result)
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
     * @return IntervenantService
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
}