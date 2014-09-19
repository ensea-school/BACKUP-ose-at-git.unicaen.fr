<?php

namespace Application\Rule\Intervenant;

/**
 * Description of PossedeDossierRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeDossierRule extends IntervenantRule
{
    public function execute()
    {
        // un vacataire doit avoir saisi un dossier
        $dossier = $this->getIntervenant()->getDossier();
        
        if (null === $dossier || !$dossier->getId()) {
            $this->setMessage("Les données personnelles de l'intervenant doivent avoir été saisies au préalable.");
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        return $statut->peutSaisirDossier();
    }
}
