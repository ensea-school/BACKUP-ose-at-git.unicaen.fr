<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of PeutCreerContratInitialRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutCreerContratInitialRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    use \Application\Traits\TypeContratAwareTrait;
    use \Application\Traits\StructureAwareTrait;
    use \Application\Service\Initializer\VolumeHoraireServiceAwareTrait;
    
    public function execute()
    {
        $this->validation = null; 
        
        $contrats = $this->getIntervenant()->getContrat($this->getTypeContrat());
        foreach ($contrats as $contrat) { /* @var $contrat \Application\Entity\Db\Contrat */
            if ($contrat->getValidation()) {
                $this->validation = $contrat->getValidation();
                $this->setMessage(sprintf("Un contrat validé le %s existe déjà pour %s.", 
                        $contrat->getValidation()->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT), 
                        $this->getIntervenant()));
                return false;
            }
        }
        /** 
         * NB: Plusieurs contrats peuvent exister pour un même intervenant et une même composante d'intervention!
         * Ce sont des "projets" de contrats ou d'avenants qui deviendront soit contrat soit avenant définitif 
         * au moment de leur validation.
         * Lorsqu'un projet de contrat est validé, il devient contrat définitif et les autres projets éventuels 
         * deviennent projets d'avenants.
         * Il ne peut exister qu'un seul contrat validé. 
         */
        
        $serviceValideMemePartiellement = $this->getServiceValideRule()->execute();
        
        if ($this->getServiceValideRule()->isRelevant() && !$serviceValideMemePartiellement) {
            $this->setMessage(sprintf("Des enseignements de %s doivent être validés au préalable.", $this->getIntervenant()));
            return false;
        }
        
        // on s'intéresse à ceux validés mais n'ayant pas faits l'objet d'un contrat
        $this->volumesHorairesDispos = [];
        foreach ($this->getServiceValideRule()->getVolumesHorairesValides() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
            if (!count($vh->getContrat())) {
                $this->volumesHorairesDispos[] = $vh;
            }
        }
        if (!count($this->volumesHorairesDispos)) {
            $this->setMessage(sprintf("Tous les volumes horaires validés de %s ont fait l'objet d'un contrat ou d'un avenant.", $this->getIntervenant()));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur;
    }
    
    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;
    
    /**
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }
    
    /**
     * @var \Application\Entity\Db\VolumeHoraire[]
     */
    private $volumesHorairesDispos;
    
    /**
     * @return \Application\Entity\Db\VolumeHoraire[]
     */
    public function getVolumesHorairesDispos()
    {
        return $this->volumesHorairesDispos ?: [];
    }
    
    private $serviceValideRule;
    
    /**
     * 
     * @return ServiceValideRule
     */
    private function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            // une validation partielle des services suffit
            $this->serviceValideRule = new ServiceValideRule($this->getIntervenant(), true);
            $this->serviceValideRule
                    ->setStructure($this->getStructure())
                    ->setTypeValidation($this->getTypeValidation())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        return $this->serviceValideRule;
    }
}
