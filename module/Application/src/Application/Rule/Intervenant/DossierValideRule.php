<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Règle métier déterminant si les données personnelles d'un intervenant sont validées.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValideRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    
    public function execute()
    {
        if (!$this->getTypeValidation()) {
            throw new \Common\Exception\LogicException("Type de validation non fourni.");
        }
        
        $validationsDossier = $this->getIntervenant()->getValidation($this->getTypeValidation());
        if (!count($validationsDossier)) {
            $this->setMessage("Les données personnelles de {$this->getIntervenant()} n'ont pas encore été validées.");
            return false;
        }
        
        $this->validation = $validationsDossier->first();
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
    
    private $validation;
    
    /**
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }
}
