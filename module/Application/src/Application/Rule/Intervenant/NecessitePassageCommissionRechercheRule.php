<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\StatutIntervenant;

/**
 * Description of NecessitePassageCommissionRechercheRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessitePassageCommissionRechercheRule extends IntervenantRule
{
    public function execute()
    {
        $dossier = $this->getIntervenant()->getDossier(); /* @var $dossier \Application\Entity\Db\Dossier */
        
        if (!$dossier->getPremierRecrutement()) {
            $this->setMessage("Il s'agit d'un premier recrutement en qualité de vacataire à l'Université de Caen.");
            return false;
        }
        
        $statut = $dossier->getStatut();
        
        $codes = array(StatutIntervenant::SALAR_PRIVE, StatutIntervenant::SALAR_PUBLIC, StatutIntervenant::NON_SALAR);
        if (!in_array($statut->getSourceCode(), $codes)) {
            $this->setMessage(sprintf("Le statut de l'intervenant (%s) ne nécessite pas le passage en Commission de la Recherche.", $statut));
            return false;
        }

        $this->setMessage(sprintf("Le statut de l'intervenant (%s) nécessite le passage en Commission de la Recherche.", $statut));
            
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
}
