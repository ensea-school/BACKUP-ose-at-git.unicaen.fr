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
    
    const INDEX_SAISIE_DOSSIER     = 'SAISIE_DOSSIER';
    const INDEX_VALIDATION_DOSSIER = 'VALIDATION_DOSSIER';
    const INDEX_SAISIE_SERVICE     = 'SAISIE_SERVICE';
    const INDEX_VALIDATION_SERVICE = 'VALIDATION_SERVICE';
    const INDEX_PIECES_JOINTES     = 'PIECES_JOINTES';
    const INDEX_CONSEIL_RESTREINT  = 'CONSEIL_RESTREINT'; 
    const INDEX_CONSEIL_ACADEMIQUE = 'CONSEIL_ACADEMIQUE'; 
    const INDEX_EDITION_CONTRAT    = 'EDITION_CONTRAT';
    const INDEX_FINAL              = 'FINAL';
    
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
    
    private $serviceValideRule;
    
    protected function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            // teste si les enseignements ont été validés, MÊME PARTIELLEMENT
            $this->serviceValideRule = new ServiceValideRule($this->getIntervenant(), true);
            $this->serviceValideRule
                    ->setTypeValidation($this->getTypeValidationService())
                    ->setStructure($this->getStructure())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        
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