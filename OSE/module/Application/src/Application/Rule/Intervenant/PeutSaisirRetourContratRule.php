<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Description of PeutSaisirRetourContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirRetourContratRule extends IntervenantRule
{
    use \Application\Service\Initializer\ContratServiceAwareTrait;
    
    private $contrat;
    
    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct($intervenant);
        $this->contrat = $contrat;
    }
    
    public function execute()
    {
        $contratToString = $this->contrat->toString(true);
            
        if (!($validation = $this->contrat->getValidation())) {
            $this->setMessage("$contratToString n'est pas encore validé.");
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataire();
    }
}