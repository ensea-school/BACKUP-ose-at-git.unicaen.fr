<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\StatutIntervenant;

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
        
        $statut = $dossier->getStatut();
        if ($statut->getSourceCode() === StatutIntervenant::RETR_UCBN) {
            $this->setMessage(sprintf("Le statut '%s' n'autorise pas la saisie de pièce justificative.", $statut));
            return false;
        }
        
        
        return true;
    }
    public function isRelevant()
    {
        return true;
    }
}