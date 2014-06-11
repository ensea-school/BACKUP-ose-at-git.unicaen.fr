<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of PeutSaisirServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirServiceRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getDossier()->getStatut();
        
        if (!$statut->permetSaisieService()) {
            $this->setMessage(sprintf("Le statut '%s' n'autorise pas la saisie de services.", $statut));
            return false;
        }
        
        return true;
    }
    public function isRelevant()
    {
        return $this->getIntervenant()->getStatut()->estVacataireNonBiatss() && $this->getIntervenant() instanceof IntervenantExterieur;
    }
}