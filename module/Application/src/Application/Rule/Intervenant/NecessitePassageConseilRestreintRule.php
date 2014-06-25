<?php

namespace Application\Rule\Intervenant;

/**
 * Description of NecessitePassageConseilRestreintRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessitePassageConseilRestreintRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$statut->estVacataire()) {
            $this->setMessage("Le passage en Conseil Restreint de la composante n'est requis que pour les vacataires (BIATSS inclus).");
            return false;
        }

        $this->setMessage(sprintf("Le statut de l'intervenant (%s) nécessite le passage en Conseil Restreint de la composante.", $statut));
            
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
