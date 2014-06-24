<?php

namespace Application\Rule\Intervenant;

/**
 * Description of PeutSaisirServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirServiceRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$statut->permetSaisieService()) {
            $this->setMessage(sprintf("Le statut '%s' n'autorise pas la saisie de services.", $statut));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        // NB: pour un intervenant non-BIATSS qui n'a pas encore saisi ses données perso, 
        // cette règle n'est pas pertinente (car il peut changer de statut à l'issu de la
        // saisie de ses données perso)
        if (!$this->getIntervenant()->getStatut()->estBiatss()) {
            $aucunDossier = new PossedeDossierRule($this->getIntervenant());
            if (!$aucunDossier->isRelevant()) {
                return true;
            }
            if (!$aucunDossier->execute()) {
                return false;
            }
        }
        
        return true;
    }
}