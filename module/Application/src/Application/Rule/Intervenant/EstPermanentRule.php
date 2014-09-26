<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantPermanent;

/**
 * Règle métier déterminant si un intervenant est un permanent ou un vacataire.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EstPermanentRule extends IntervenantRule
{
    public function execute()
    {
        $statut = $this->getIntervenant()->getStatut();
        
        if (!$this->getIntervenant() instanceof IntervenantPermanent || !$statut->estPermanent()) {
            $this->setMessage(sprintf("%s n'est pas un intervenant permanent.", $this->getIntervenant()));
            return false;
        }

        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
