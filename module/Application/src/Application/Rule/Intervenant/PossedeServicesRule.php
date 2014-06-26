<?php

namespace Application\Rule\Intervenant;

/**
 * Description of PossedeServicesRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeServicesRule extends IntervenantRule
{
    use \Application\Traits\AnneeAwareTrait;
    
    public function execute()
    {
        $service = $this->getIntervenant()->getService($this->getAnnee());
        
        if (!count($service)) {
            $this->setMessage(sprintf("Aucun enseignement prévisionnel n'a été saisi concernant %s.", $this->getIntervenant()));
            return false;
        }
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}
