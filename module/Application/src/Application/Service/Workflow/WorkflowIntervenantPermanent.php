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
     * Création des différentes étapes composant le workflow.
     * 
     * @param bool $partial En spécifiant <code>true</code>, la création des étapes
     * ne va pas au-delà de la première étape non franchie.
     * @return self
     */
    protected function createSteps()
    {        
        $this->steps = array();
        
        /**
         * Saisie des services
         */
        $peutSaisirServices = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirServices->isRelevant() || $peutSaisirServices->execute()) {
            $transitionRule = new PossedeServicesRule($this->getIntervenant());
            $this->addStep(
                    self::KEY_SAISIE_SERVICE,
                    new Step\SaisieServiceStep(),
                    $transitionRule
            );
        }
        
        /**
         * Validation des services
         */
        $peutSaisirService = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirService->isRelevant() || $peutSaisirService->execute()) {
            $transitionRule = $this->getServiceValideRule();
            $this->addStep(
                    self::KEY_VALIDATION_SERVICE,
                    new Step\ValidationServiceStep(),
                    $this->getServiceValideRule()
            );
        }
        
        /**
         * Agrements des différents conseils
         */
        $necessiteAgrement = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrement NecessiteAgrementRule */
        $necessiteAgrement->setIntervenant($this->getIntervenant());
        foreach ($necessiteAgrement->getTypesAgrementAttendus() as $typeAgrement) {
            $transitionRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
            $transitionRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement)
                ->setStructure($this->getStructure());

            $this->addStep(
                     'KEY_' . $typeAgrement->getCode(),
                    new Step\AgrementStep($typeAgrement),
                    $transitionRule
            );
        }
        
//        $this->addStep(
//                self::KEY_FINAL,
//                new Step\FinalStep(),
//                null
//        );
            
        return $this;
    }
}