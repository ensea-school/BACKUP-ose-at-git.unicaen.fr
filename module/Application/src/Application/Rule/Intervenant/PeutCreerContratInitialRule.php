<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Constants;

/**
 * Règle métier déterminant si un intervenant peut faire l'objet d'une création de contrat initial.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutCreerContratInitialRule extends PeutCreerContratAbstractRule
{
    public function execute()
    {
        $this->validation = null; 
        
        $contrats = $this->getIntervenant()->getContrat($this->getTypeContrat());
        foreach ($contrats as $contrat) { /* @var $contrat Contrat */
            if ($contrat->getValidation()) {
                $this->validation = $contrat->getValidation();
                $this->setMessage(sprintf("Un contrat validé le %s existe déjà pour %s.", 
                        $contrat->getValidation()->getHistoModification()->format(Constants::DATETIME_FORMAT), 
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
        return !$this->getIntervenant()->estPermanent();
    }
    
    /**
     * Retourne le type de contrat concerné.
     * 
     * @return TypeContrat
     */
    public function getTypeContrat()
    {
        return $this->getServiceLocator()->get('ApplicationTypeContrat')->getRepo()->findOneByCode(TypeContrat::CODE_CONTRAT);
    }
    
    /**
     * @var Validation
     */
    private $validation;
    
    /**
     * @return Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }
}
