<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of DossierTransmisConseilRestreintRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierTransmisConseilRestreintRule extends IntervenantRule
{
    public function execute()
    {
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
}
