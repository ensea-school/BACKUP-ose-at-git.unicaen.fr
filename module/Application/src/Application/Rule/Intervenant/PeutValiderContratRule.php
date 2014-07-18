<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Description of PeutValiderContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutValiderContratRule extends IntervenantRule
{
    private $contrat;
    
    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct($intervenant);
        $this->contrat = $contrat;
    }
    
    public function execute()
    {
        if (($validation = $this->contrat->getValidation())) {
            $contratToString = $this->contrat->toString(true);
            $dateValidation  = $validation->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT);
            $this->setMessage("$contratToString est a déjà été validé le $dateValidation par {$validation->getHistoModificateur()}.");
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataire();
    }
}