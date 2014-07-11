<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeValidation;

/**
 * Description of PeutValiderServiceRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutValiderServiceRule extends IntervenantRule
{
    private $typeValidation;
    
    public function __construct(Intervenant $intervenant, TypeValidation $typeValidation)
    {
        parent::__construct($intervenant);
        $this->typeValidation = $typeValidation;
    }
    
    /**
     * 
     * @todo Cette règle devrait se concentrer uniquement sur les services, pas les données perso!
     * @todo Problème potentiel : il est permis de valider les services ssi il est permis de saisir des services
     * @return boolean
     */
    public function execute()
    {
        switch ($this->typeValidation->getCode()) {
            case TypeValidation::CODE_DONNEES_PERSO_PAR_COMP:
                $peutSaisirDossier = new PeutSaisirDossierRule($this->getIntervenant());
                if (!$peutSaisirDossier->execute()) {
                    $this->setMessage($peutSaisirDossier->getMessage());
                    return false;
                }
                break;
            case TypeValidation::CODE_SERVICES_PAR_COMP:
                $permetSaisieService = new PeutSaisirServiceRule($this->getIntervenant());
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