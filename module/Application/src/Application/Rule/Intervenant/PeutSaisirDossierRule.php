<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\StatutIntervenant;

/**
 * Description of PeutSaisirServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirDossierRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        $ok = $this->getIntervenant() instanceof IntervenantExterieur 
                && ($statut->estVacataireNonBiatss() || $statut->getSourceCode() === StatutIntervenant::AUTRES);
        
        if (!$ok) {
            $this->setMessage(sprintf("Le statut '%s' n'autorise pas la saisie de données personnelles.", $statut));
            return false;
        }
        
        return true;
    }
    public function isRelevant()
    {
        return true;
    }
}