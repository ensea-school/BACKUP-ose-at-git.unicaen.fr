<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Service;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\VolumeHoraire;

/**
 * Règle métier déterminant si un intervenant peut faire l'objet d'une création d'avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutCreerAvenantRule extends PeutCreerContratAbstractRule
{
    public function execute()
    {
        $contrats = $this->getIntervenant()->getContrat($this->getTypeContrat());
        if (!count($contrats)) {
            $this->setMessage(sprintf("Un contrat initial doit exister pour pouvoir créer un avenant à %s.", $this->getIntervenant()));
            return false;
        }
        
        $this->getServiceValideRule()->execute();
        
        // on s'intéresse aux enseignements validés mais n'ayant pas faits l'objet d'un avenant
        $this->volumesHorairesDispos = [];
        foreach ($this->getServiceValideRule()->getVolumesHorairesValides() as $vh) { /* @var $vh VolumeHoraire */
            if (!$vh->getMotifNonPaiement() && !count($vh->getContrat())) {
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
     * Retourne le type de contrat "contrat".
     * 
     * @return TypeContrat
     */
    protected function getTypeContrat()
    {
        return $this->getServiceLocator()->get('ApplicationTypeContrat')->getRepo()->findOneByCode(TypeContrat::CODE_CONTRAT);
    }
    
    /**
     * @return Service[]
     */
    public function getServicesDispos()
    {
        $servicesDispos = [];
        foreach ($this->getVolumesHorairesDispos() as $vh) { /* @var $vh VolumeHoraire */
            $servicesDispos[$vh->getService()->getId()] = $vh->getService();
        }
        
        return $servicesDispos;
    }
}
