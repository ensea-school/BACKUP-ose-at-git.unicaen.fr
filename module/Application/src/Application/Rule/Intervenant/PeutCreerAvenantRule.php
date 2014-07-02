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
        
        $this->getServiceValideRule()->execute();
        
        // on s'intéresse à ceux validés mais n'ayant pas faits l'objet d'un avenant
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
        return true;
    }
    
    /**
     * @var \Application\Entity\Db\Service[]
     */
    private $servicesDispos;
    
    /**
     * @return \Application\Entity\Db\Service[]
     */
    public function getServicesDispos()
    {
        if (null === $this->servicesDispos) {
            $this->servicesDispos = [];
            foreach ($this->getVolumesHorairesDispos() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                $this->servicesDispos[$vh->getService()->getId()] = $vh->getService();
            }
        }
        return $this->servicesDispos;
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
            $this->serviceValideRule = new ServiceValideRule($this->getIntervenant());
            $this->serviceValideRule
                    ->setStructure($this->getStructure())
                    ->setTypeValidation($this->getTypeValidation())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        return $this->serviceValideRule;
    }
}
