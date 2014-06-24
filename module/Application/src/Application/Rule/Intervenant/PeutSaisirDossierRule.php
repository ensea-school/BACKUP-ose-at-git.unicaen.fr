<?php

namespace Application\Rule\Intervenant;

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
        $ok = $statut->estVacataireNonBiatss() || $statut->estAutre();
        
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