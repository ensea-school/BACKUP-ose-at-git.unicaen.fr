<?php

namespace Application\Rule\Intervenant;

/**
 * Règle métier déterminant si un intervenant nécessite l'établissement d'un contrat/avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteContratRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$statut->necessiteContrat()) {
            $this->setMessage(sprintf("Le statut &laquo; %s &raquo; ne nécessite pas l'établissement d'un contrat.", $statut));
            return false;
        }

        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
