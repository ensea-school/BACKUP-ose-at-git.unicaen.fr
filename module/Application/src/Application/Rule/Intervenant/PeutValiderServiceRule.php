<?php

namespace Application\Rule\Intervenant;

use Application\Rule\AbstractRule;
use Application\Entity\Db\TypeValidation;

/**
 * Description of PeutValiderServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutValiderServiceRule extends AbstractRule
{
    use \Application\Traits\IntervenantAwareTrait;
    use \Application\Traits\TypeValidationAwareTrait;
    
    /**
     * 
     * @todo Cette règle devrait se concentrer uniquement sur les services, pas les données perso!
     * @todo Problème potentiel : il est permis de valider les services ssi il est permis de saisir des services
     * @return boolean
     */
    public function execute()
    {
        switch ($this->typeValidation->getCode()) {
            case TypeValidation::CODE_DONNEES_PERSO:
//                $peutSaisirDossier = new PeutSaisirDossierRule($this->getIntervenant());
                $peutSaisirDossier = $this->getServiceLocator()->get('PeutSaisirDossierRule')->setIntervenant($this->getIntervenant());
                if (!$peutSaisirDossier->execute()) {
                    $this->setMessage($peutSaisirDossier->getMessage());
                    return false;
                }
                break;
            case TypeValidation::CODE_ENSEIGNEMENT:
//                $permetSaisieService = new PeutSaisirServiceRule($this->getIntervenant());
                $permetSaisieService = $this->getServiceLocator()->get('PeutSaisirServiceRule')->setIntervenant($this->getIntervenant());
                if (!$permetSaisieService->execute()) {
                    $this->setMessage($permetSaisieService->getMessage());
                    return false;
                }
                break;
            default:
                break;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}