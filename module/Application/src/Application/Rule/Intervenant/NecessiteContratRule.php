<?php

namespace Application\Rule\Intervenant;

/**
 * Description of NecessiteContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteContratRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$statut->estVacataireNonBiatss()) {
            $this->setMessage(sprintf("Le statut '%s' ne nécessite pas l'édition d'un contrat.", $statut));
            return false;
        }

        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
