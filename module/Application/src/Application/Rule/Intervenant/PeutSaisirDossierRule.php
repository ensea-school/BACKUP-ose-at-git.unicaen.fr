<?php

namespace Application\Rule\Intervenant;

/**
 * Règle métier déterminant si un intervenant peut saisir des données personnelles.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirDossierRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$statut->getPeutSaisirDossier()) {
            $this->setMessage(sprintf("Le statut &laquo; %s &raquo; n'autorise pas la saisie de données personnelles.", $statut));
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}