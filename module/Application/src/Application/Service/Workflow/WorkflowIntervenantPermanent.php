<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantPermanent;
use Application\Rule\Intervenant\PeutSaisirServiceRule;
use Application\Rule\Intervenant\PossedeServicesRule;
use Application\Service\Workflow\Step;
use Common\Exception\LogicException;

/**
 * Implémentation du workflow concernant un intervenant permanent.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowIntervenantPermanent extends WorkflowIntervenant
{    
    /**
     * 
     * @param IntervenantPermanent $intervenant
     * @throws LogicException
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        if (!$intervenant instanceof IntervenantPermanent) {
            throw new LogicException("Intervenant permanent attendu.");
        }

        parent::setIntervenant($intervenant);
        
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    protected function createSteps()
    {        
        $this->steps = array();
        
        $peutSaisirServices = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirServices->isRelevant() || $peutSaisirServices->execute()) {
            $this->addStep(
                    self::INDEX_SAISIE_SERVICE,
                    new Step\SaisieServiceStep(),
                    new PossedeServicesRule($this->getIntervenant())
            );
        }
        
        $peutSaisirService = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirService->isRelevant() || $peutSaisirService->execute()) {
            $this->addStep(
                    self::INDEX_VALIDATION_SERVICE,
                    new Step\ValidationServiceStep(),
                    $this->getServiceValideRule()
            );
        }
        
        $necessiteAgrement = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrement NecessiteAgrementRule */
        $necessiteAgrement
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($this->getTypeAgrementConseilAcademique());
        if (!$necessiteAgrement->isRelevant() || $necessiteAgrement->execute()) {
            $transitionRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
            $transitionRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($this->getTypeAgrementConseilAcademique());
            
            $this->addStep(
                    self::INDEX_CONSEIL_ACADEMIQUE,
                    new Step\AgrementStep($this->getTypeAgrementConseilAcademique()),
                    $transitionRule
            );
        }
        
//        $this->addStep(
//                self::INDEX_FINAL,
//                new Step\FinalStep(),
//                null
//        );
            
        return $this;
    }
}