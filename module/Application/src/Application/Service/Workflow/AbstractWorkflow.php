<?php

namespace Application\Service\Workflow;

use Application\Rule\RuleInterface;
use Application\Service\AbstractService;
use Application\Service\Workflow\Step\Step;

/**
 * Processus implémentant le workflow concernant un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractWorkflow extends AbstractService
{
    /**
     * @return self
     */
    abstract protected function createSteps();
    
    /**
     * @return self
     */
    protected function processSteps()
    {
        if (!$this->steps) {
            $this->createSteps();
        }
        
        $this->setCurrentStep(null);
        
        foreach ($this->getSteps() as $index => $step) { /* @var $step Step */
            if (($rule = $this->getConditions()[$index]) && $rule->isRelevant() && !$rule->execute()) {
                $this->setCurrentStep($step->setIsCurrent());
                break;
            }
            else {
                $step->setDone();
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * @param string $index
     * @param string $name
     * @param string $description
     * @param string $route
     * @param RuleInterface $rule
     * @return Step
     */
    protected function addStep($key, Step $step, RuleInterface $rule = null)
    {   
        $step->setIndex(count($this->getSteps()) + 1);
        
        $this->steps[$key]      = $step;
        $this->conditions[$key] = $rule;

        return $this;
    }
    
    /**
     * @return Step
     */
    protected function getStep($key)
    {
        return $this->getSteps()[$key];
    }
    
    /**
     * @var Step[]
     */
    protected $steps;
    
    /**
     * @return Step[]
     */
    public function getSteps()
    {
        if (null === $this->steps) {
            $this->createSteps()->processSteps();
        }
        return $this->steps;
    }

    /**
     * 
     * @param Step[] $steps
     * @return \Application\Service\Workflow\AbstractWorkflow
     */
//    protected function setSteps(array $steps)
//    {
//        $this->steps = $steps;
//        return $this;
//    }

    /**
     * @var \Application\Rule\RuleInterface[]
     */
    protected $conditions;
    
    /**
     * @return \Application\Rule\RuleInterface[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * 
     * @param \Application\Rule\RuleInterface[] $conditions
     * @return \Application\Service\Workflow\AbstractWorkflow
     */
//    protected function setConditions(array $conditions)
//    {
//        $this->conditions = $conditions;
//        return $this;
//    }

    /**
     * @var Step
     */
    private $currentStep;
    
    /**
     * @return Step
     */
    public function getCurrentStep()
    {
        if (null === $this->currentStep) {
            $this->processSteps();
        }
        return $this->currentStep;
    }

    /**
     * 
     * @param \Application\Service\Workflow\Step\Step $currentStep
     * @return \Application\Service\Workflow\AbstractWorkflow
     */
    protected function setCurrentStep(Step $currentStep = null)
    {
        $this->currentStep = $currentStep;
        return $this;
    }
    
    /**
     * @param \Application\Service\Workflow\Step\Step $step
     * @return Step
     */
    public function getNextStep(Step $step = null)
    {
        if (null === $step) {
            $step = $this->getCurrentStep();
            if (null === $step) {
                return null; 
            }
        }
        
        $slice = array_slice($this->getSteps(), $step->getIndex(), 1);
        if (!$slice) {
            return null;
        }
        
        return current($slice);
    }
    
    /**
     * @return Step
     */
    public function getLastStep()
    {
        $slice = array_slice($this->getSteps(), -1);
        if (!$slice) {
            throw new \Common\Exception\LogicException("Aucune étape dans le workflow!");
        }
        
        return current($slice);
    }
    
    /**
     * 
     * @param string $route
     * @return Step
     */
    public function getStepForRoute($route)
    {
        foreach ($this->getSteps() as $step) {
            if ($route === $step->getRoute()) {
                return $step;
            }
        }
        
        return null;
    }
    
    /**
     * 
     * @return Step
     */
    public function getStepForCurrentRoute()
    {
        $application = $this->getServiceLocator()->get('Application'); /* @var $application \Zend\Mvc\Application */
        $route = $application->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        
        return $this->getStepForRoute($route);
    }
    
    /**
     * 
     * @param \Application\Service\Workflow\Step\Step $step
     * @return bool
     */
    protected function containsStep(Step $step)
    {
        foreach ($this->getSteps() as $s) {
            if ($step === $s) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param \Application\Service\Workflow\Step\Step $step
     * @return boolean
     */
    public function isStepBeforeCurrentStep(Step $step)
    {
        if (!$this->containsStep($step)) {
            throw new \Common\Exception\RuntimeException("Etape spécifiée non trouvée dans le workflow.");
        }
        
        $currentStep = $this->getCurrentStep();
        if (null === $currentStep) {
            // pas d'étape courante, le workflow est terminé :
            // l'étape spécifiée est considérée comme avant l'étape courante
            return true; 
        }
        
        return $step->getIndex() < $currentStep->getIndex();
    }
    
    /**
     * 
     * @param \Application\Service\Workflow\Step\Step $step
     * @return boolean
     */
    public function isStepAfterCurrentStep(Step $step)
    {
        if (!$this->containsStep($step)) {
            throw new \Common\Exception\RuntimeException("Etape spécifiée non trouvée dans le workflow.");
        }
        
        $currentStep = $this->getCurrentStep();
        if (null === $currentStep) {
            // pas d'étape courante, le workflow est terminé :
            // l'étape spécifiée ne peut être située après l'étape courante
            return false; 
        }
        
        return $step->getIndex() > $currentStep->getIndex();
    }
    
    /**
     * @return \Zend\Mvc\Controller\Plugin\Url
     */
    protected function getHelperUrl()
    {
        return $this->getServiceLocator()->get('ControllerPluginManager')->get('Url');
    }
}