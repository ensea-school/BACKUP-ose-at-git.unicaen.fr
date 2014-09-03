<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\TypeAgrement;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\RoleAwareTrait;
use Application\Service\Workflow\Step\Step;
use Application\Rule\Intervenant\ServiceValideRule;
use Application\Acl\ComposanteDbRole;

/**
 * Implémentation du workflow concernant un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class WorkflowIntervenant extends AbstractWorkflow
{
    use IntervenantAwareTrait;
    use RoleAwareTrait;
    
    const KEY_SAISIE_DONNEES     = 'KEY_SAISIE_DOSSIER';
    const KEY_VALIDATION_DONNEES = 'KEY_VALIDATION_DONNEES';
    const KEY_SAISIE_SERVICE     = 'KEY_SAISIE_SERVICE';
    const KEY_VALIDATION_SERVICE = 'KEY_VALIDATION_SERVICE';
    const KEY_PIECES_JOINTES     = 'KEY_PIECES_JOINTES';
    const KEY_CONSEIL_RESTREINT  = 'KEY_CONSEIL_RESTREINT';  // NB: 'KEY_' . code type agrément
    const KEY_CONSEIL_ACADEMIQUE = 'KEY_CONSEIL_ACADEMIQUE'; // NB: 'KEY_' . code type agrément
    const KEY_EDITION_CONTRAT    = 'KEY_EDITION_CONTRAT';
    const KEY_FINAL              = 'KEY_FINAL';
    
    /**
     * Retourne l'URL correspondant à l'étape spécifiée.
     * 
     * @param \Application\Service\Workflow\Step $step
     * @return string
     */
    public function getStepUrl(Step $step)
    {
        $params = array_merge(
                $step->getRouteParams(), 
                array('intervenant' => $this->getIntervenant()->getSourceCode()));
        
        $url = $this->getHelperUrl()->fromRoute($step->getRoute(), $params);
        
        return $url;
    }
    
    /**
     * Retourne l'URL correspondant à l'étape courante.
     * 
     * @return string
     */
    public function getCurrentStepUrl()
    {
        if (!$this->getCurrentStep()) {
            return null;
        }
        return $this->getStepUrl($this->getCurrentStep());
    }
    
    protected $serviceValideRule;
    
    protected function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = new ServiceValideRule();
        }
        // teste si les enseignements ont été validés, MÊME PARTIELLEMENT
        $this->serviceValideRule
                ->setMemePartiellement()
                ->setIntervenant($this->getIntervenant())
                ->setTypeValidation($this->getTypeValidationService())
                ->setStructure($this->getStructure())
                ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        
        return $this->serviceValideRule;
    }
    
    /**
     * 
     * @return \Application\Entity\Db\Structure
     */
    protected function getStructure()
    {
        if ($this->getRole() instanceof ComposanteDbRole) {
            return $this->getRole()->getStructure();
        }
        
        return null;
    }
    
    /**
     * 
     * @return \Application\Service\TypeValidation
     */
    protected function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
    
    /**
     * 
     * @return \Application\Service\TypeAgrement
     */
    protected function getServiceTypeAgrement()
    {
        return $this->getServiceLocator()->get('ApplicationTypeAgrement');
    }
    
    /**
     * 
     * @return \Application\Service\VolumeHoraire
     */
    protected function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    } 
    
    /**
     * @return TypeValidation
     */
    protected function getTypeValidationService()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_SERVICES_PAR_COMP);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();
        
        return $typeValidation;
    }
    
    /**
     * @return TypeAgrement
     */
    protected function getTypeAgrementConseilRestreint()
    {
        $qb = $this->getServiceTypeAgrement()->finderByCode(TypeAgrement::CODE_CONSEIL_RESTREINT);
        $type = $qb->getQuery()->getSingleResult();
        
        return $type;
    }
    
    /**
     * @return TypeAgrement
     */
    protected function getTypeAgrementConseilAcademique()
    {
        $qb = $this->getServiceTypeAgrement()->finderByCode(TypeAgrement::CODE_CONSEIL_ACADEMIQUE);
        $type = $qb->getQuery()->getSingleResult();
        
        return $type;
    }
}