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
     * Parcourt les étapes pour déterminer l'étape courante (non encore réalisée).
     * 
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
     * Ajoute une étape au workflow, associée à une condition de réalisation.
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
     * Retourne l'étape du workflow associée à la clé spécifiée.
     * 
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
     * Reoutne toutes les étapes du workflow.
     * 
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
     * Conditions de réalisation (règles métier) associées à chaque étape du workflow.
     * Une étape est considérée comme réalisée si sa "condition" est satisfaite.
     * 
     * @var \Application\Rule\RuleInterface[]
     */
    protected $conditions;
    
    /**
     * Retourne les conditions de réalisation (les règles métier) associées à chaque étape du workflow.
     * Une étape est considérée comme réalisée si sa "condition" est satisfaite.
     * 
     * @return \Application\Rule\RuleInterface[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @var Step
     */
    private $currentStep;
    
    /**
     * Retourne l'étape courante du workflow, i.e. l'étape où en est l'intervenant
     * dans le workflow.
     * 
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
     * Spécifie l'étape où en est l'intervenant dans le workflow.
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
     * Retourne l'étape du workflow située juste après l'étape spécifiée.
     * 
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
     * Retourne la dernière étape du workflow.
     * 
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
     * Retourne l'étape correspondant à la route spécifiée.
     * 
     * @param string $route
     * @return Step|null
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
     * Retourne l'étape correspondant à la route courante (i.e. la route correspondant
     * à la requête courante).
     * 
     * @return Step|null
     */
    public function getStepForCurrentRoute()
    {
        $application = $this->getServiceLocator()->get('Application'); /* @var $application \Zend\Mvc\Application */
        $route = $application->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        
        return $this->getStepForRoute($route);
    }
    
    /**
     * Teste si le workflow contient bien l'étape spécifiée.
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
     * Teste si l'étape spécifiée se situe avant l'étape courante dans le workflow.
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
     * Teste si l'étape spécifiée se situe après l'étape courante dans le workflow.
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