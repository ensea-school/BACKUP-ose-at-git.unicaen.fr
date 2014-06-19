<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of PeutSaisirServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirPieceJointeRule extends IntervenantRule
{
    public function execute()
    {
        $dossier = null;
        if ($this->getIntervenant() instanceof IntervenantExterieur) {
            $dossier = $this->getIntervenant()->getDossier();
        }
        if (!$dossier) {
            $this->setMessage("La saisie de pièce justificative requiert au préalable la saisie des données personnelles.");
            return false;
        }
        
        $statut = $this->getIntervenant()->getStatut();
        if (!$statut->estVacataireNonBiatss()) {
            $this->setMessage(sprintf("Le statut '%s' ne nécessite pas la fourniture de pièces justificatives.", $statut));
            return false;
        }
        
        return true;
    }
    public function isRelevant()
    {
        return true;
    }
}