<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Contrat;

/**
 * Description of PeutExporterContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutExporterContratRule extends IntervenantRule
{
    private $contrat;
    
    public function __construct(Intervenant $intervenant, Contrat $contrat)
    {
        parent::__construct($intervenant);
        $this->contrat = $contrat;
    }
    
    public function execute()
    {
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataire();
    }
}