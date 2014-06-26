<?php

namespace Application\Rule\Intervenant;

/**
 * Description of PeutCreerAvenantRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutCreerAvenantRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    use \Application\Traits\TypeContratAwareTrait;
    use \Application\Traits\StructureAwareTrait;
    use \Application\Service\Initializer\VolumeHoraireServiceAwareTrait;
    
    public function execute()
    {
        $contrats = $this->getIntervenant()->getContrat($this->getTypeContrat());
        if (!count($contrats)) {
            $this->setMessage(sprintf("Un contrat initial doit exister pour pouvoir créer un avenant à %s.", $this->getIntervenant()));
            return false;
        }
        
        if ($this->getServiceValideRule()->isRelevant() && !$this->getServiceValideRule()->execute()) {
//            $this->setMessage(sprintf("Tous les enseignements de %s doivent être validés au préalable.", $this->getIntervenant()));
            $this->setMessage($this->getServiceValideRule()->getMessage());
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
    
    /**
     * 
     * @return array|null
     */
    public function getVolumesHorairesNonValides()
    {
        return $this->getServiceValideRule()->getVolumesHorairesNonValides();
    }
    
    private $serviceValideRule;
    
    /**
     * 
     * @return ServiceValideRule
     */
    private function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = new ServiceValideRule($this->getIntervenant(), true);
            $this->serviceValideRule
                    ->setStructure($this->getStructure())
                    ->setTypeValidation($this->getTypeValidation())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        return $this->serviceValideRule;
    }
}
