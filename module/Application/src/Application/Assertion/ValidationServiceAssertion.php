<?php

namespace Application\Assertion;

use Application\Rule\Validation\ValidationEnseignementRule;

/**
 * Assertions concernant la validation d'enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationServiceAssertion extends ValidationEnsRefAbstractAssertion
{
    /**
     * @return boolean
     */
//    protected function assertEntity()
//    {
//        /*********************************************************
//         *                      Rôle administrateur
//         *********************************************************/
//        if ($this->role instanceof AdministrateurRole) {
//            return true;
//        }
//
//        $intervenant          = $this->resource->getIntervenant();
//        $structureValidation  = $this->resource->getStructure();
//        $structureIntervenant = $intervenant->getStructure();
//        $structureRole        = $this->role->getStructure();
//        $typeVolumeHoraire    = $this->getTypeVolumeHoraire();
//
//        /*********************************************************
//         *                      Rôle Composante
//         *********************************************************/
//        if ($this->role instanceof ComposanteRole) {
//            if ('read' === $this->privilege) {
//                return true; // les composantes voient tout
//            }
//
//            switch ($typeVolumeHoraire->getCode()) {
//                // Enseignements PRÉVUS :
//                // - intervenant permanent : validation par la composante d'affectation de l'intervenant ;
//                // - intervenant vacataire : validation par chaque composante d'intervention des enseignements la concernant.
//                case TypeVolumeHoraire::CODE_PREVU:
//                    if ( $intervenant->estPermanent() && $structureRole === $structureIntervenant || 
//                        !$intervenant->estPermanent() && $structureRole === $structureValidation) {
//                        return true;
//                    }
//                    break;
//                // Enseignements REALISES :
//                case TypeVolumeHoraire::CODE_REALISE:
//                    break;
//                default:
//                    throw new LogicException("Type de volume horaire inattendu.");
//            }
//        }
//
//        /*********************************************************
//         *                      Rôle Superviseur
//         *********************************************************/
//        if ($this->role instanceof EtablissementRole) {
//            if ('read' === $this->privilege) {
//                return true; // les superviseurs voient tout
//            }
//        }
//
//        /*********************************************************
//         *                      Rôle DRH
//         *********************************************************/
//        if ($this->role instanceof DrhRole) {
//            if ('read' === $this->privilege) {
//                return true; // ils voient tout à la DRH
//            }
//        }
//
//        /*********************************************************
//         *                      Rôle Intervenant
//         *********************************************************/
//        if ($this->role instanceof IntervenantRole) {
//            return $this->assertEntityForIntervenantRole();
//        }
//        
//        return false;
//    }
//    
//    /**
//     * @return boolean
//     */
//    protected function assertEntityForIntervenantRole()
//    {
//        if ('read' === $this->privilege) {
//            return true;
//        }
//        
//        return false;
//    }
    protected function assertEntityOld()
    {
        $rule = $this->getServiceLocator()->get('ValidationEnseignementRule')
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeVolumeHoraire($this->getTypeVolumeHoraire())
                ->setRole($this->role)
                ->execute();
//        var_dump("isAllowed({$this->privilege}) = " . (int)$rule->isAllowed($this->privilege));

        return $rule->isAllowed($this->privilege);
    }
    
    /**
     * @return TypeVolumeHoraire
     */
    protected function getTypeVolumeHoraire()
    {
        $tvh = $this->getMvcEvent()->getParam('typeVolumeHoraire');
        
        if (! $tvh) {
            throw new LogicException(
                    "Aucun type de volume horaire spécifié dans l'événement MVC.");
        }
        
        return $tvh;
    }
}