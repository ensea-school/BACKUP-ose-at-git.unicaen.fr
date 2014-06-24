<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\StatutIntervenant;

/**
 * Description of NecessiteMailStructureAffectationRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteMailStructureAffectationRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        $codes = array(StatutIntervenant::BIATSS);
        if (!in_array($statut->getSourceCode(), $codes)) {
            $this->setMessage(sprintf("Le statut '%s' ne nécessite pas l'envoi d'un mail à la structure d'affectation.", $statut));
            return false;
        }

        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
