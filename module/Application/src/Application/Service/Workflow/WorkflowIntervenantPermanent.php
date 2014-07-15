<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantPermanent;
use Application\Rule\Intervenant\PeutSaisirServiceRule;
use Application\Rule\Intervenant\PossedeServicesRule;
use Application\Rule\Intervenant\ServiceValideRule;
use Application\Service\Workflow\Step;
use Common\Exception\LogicException;

/**
 * Implémentation du workflow concernant un intervenant permanent.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowIntervenantPermanent extends WorkflowIntervenant
{
    const INDEX_SAISIE_SERVICE     = 20;
    const INDEX_VALIDATION_SERVICE = 50;
    const INDEX_FINAL              = 100;
    
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
        
//        $passageCR = new \Application\Rule\Intervenant\NecessitePassageCommissionRechercheRule($this->getIntervenant());
//        if ($passageCR->isRelevant() && $passageCR->execute()) {
//            $this->addStep(
//                    self::INDEX_PASSAGE_CR,
//                    "Passage en Commission de la Recherche", 
//                    null,
//                    null,
//                    new \Application\Rule\Intervenant\NecessitePassageCommissionRechercheRule($this->getIntervenant())
//            );
//        }
        
//        $this->addStep(
//                self::INDEX_FINAL,
//                new Step\FinalStep(),
//                null
//        );
            
        return $this;
    }
}