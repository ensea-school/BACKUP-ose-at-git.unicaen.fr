<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of PeutCreerContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutCreerContratRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    use \Application\Traits\TypeContratAwareTrait;
    use \Application\Traits\StructureAwareTrait;
    use \Application\Service\Initializer\VolumeHoraireServiceAwareTrait;
    
    public function execute()
    {
        $contrats = $this->getIntervenant()->getContrat($this->getTypeContrat());
        if (count($contrats)) {
            $this->setMessage(sprintf("Un contrat existe déjà pour %s.", $this->getIntervenant()));
            return false;
        }
        
        if ($this->getServiceValideRule()->isRelevant() && !$this->getServiceValideRule()->execute()) {
            $this->setMessage(sprintf("Les enseignements de %s doivent être validés au préalable.", $this->getIntervenant()));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur;
    }
    
    private $serviceValideRule;
    
    /**
     * 
     * @return ServiceValideRule
     */
    private function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = new ServiceValideRule($this->getIntervenant());
            $this->serviceValideRule
                    ->setStructure($this->getStructure())
                    ->setTypeValidation($this->getTypeValidation())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        return $this->serviceValideRule;
    }
}
