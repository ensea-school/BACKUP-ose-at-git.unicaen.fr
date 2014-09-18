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

        if (! $statut->getPeutSaisirService()) {
            $this->setMessage(sprintf("Le statut &laquo; %s &raquo; n'autorise pas la saisie d'enseignement.", $statut));
            return false;
        }

        return true;
    }

    public function isRelevant()
    {
//        // NB: pour un intervenant non-BIATSS qui n'a pas encore saisi ses données perso, 
//        // cette règle n'est pas pertinente (car il peut changer de statut à l'issu de la
//        // saisie de ses données perso)
//        if (!$this->getIntervenant()->getStatut()->estBiatss()) {
//            $possedeDossier = new PossedeDossierRule($this->getIntervenant());
//            if (!$possedeDossier->isRelevant()) {
//                return true;
//            }
//            if (!$possedeDossier->execute()) {
//                return false;
//            }
//        }

        return true;
    }
}