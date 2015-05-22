<?php

namespace Application\Assertion;

use Application\Rule\Validation\ValidationReferentielRule;

/**
 * Assertions concernant la validation de référentiel.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationReferentielAssertion extends ValidationEnsRefAbstractAssertion
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
//        $structureIntervenant = $this->resource->getIntervenant()->getStructure();
//
//        /*********************************************************
//         *                      Rôle Composante
//         *********************************************************/
//        if ($this->role instanceof ComposanteRole) {
//            if ('read' === $this->privilege) {
//                return true; // les composantes voient tout
//            }
//            
//            $structureRole = $this->role->getStructure();
//            
//            if ($structureRole === $structureIntervenant) {
//                return true;
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
        $rule = $this->getServiceLocator()->get('ValidationReferentielRule')
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
        if (!count($this->resource->getVolumeHoraireReferentiel())) {
            throw new LogicException(
                    "Impossible de déterminer le type de volume horaire car la validation ne possède aucun volume horaire.");
        }
        
        return $this->resource->getVolumeHoraireReferentiel()[0]->getTypeVolumeHoraire();
    }
}