<?php

namespace Application\Rule\Intervenant;

/**
 * Règle métier déterminant si des enseignements peuvent être saisis pour un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirServiceRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();

        if (! $statut->getPeutSaisirService()) {
            $this->setMessage(sprintf("Le statut &laquo; %s &raquo; n'autorise pas la saisie d'enseignement.", $statut));
            return false;
        }

        return true;
    }

    /**
     * Pour un intervenant qui n'a pas encore saisi ses données perso, 
     * cette règle n'est pas pertinente car il peut changer de statut à l'issu de la
     * saisie de ses données perso.
     * 
     * @return boolean
     */
    public function isRelevant()
    {
        $peutSaisirDossier = new PeutSaisirDossierRule($this->getIntervenant());
        if ($peutSaisirDossier->isRelevant() && $peutSaisirDossier->execute()) {
            return false;
        }
        
        return true;
    }
}