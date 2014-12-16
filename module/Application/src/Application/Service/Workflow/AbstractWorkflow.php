<?php

namespace Application\Service\Workflow;

use Application\Rule\RuleInterface;
use Application\Service\AbstractService;
use Application\Service\Workflow\Step\Step;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use Zend\Mvc\Application;
use Zend\Mvc\Controller\Plugin\Url;

/**
 * Processus implémentant le workflow concernant un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractWorkflow extends AbstractService
{
    /**
     * Conditions (règles métier) :
     * - de pertinence de chaque étape : une étape est pertinente si cette condition est satisfaite.
     * - de réalisation de chaque étape : une étape est considérée comme réalisée si cette condition est satisfaite.
     * 
     * @var array clé => ['relevance' => RuleInterface, 'crossing' => RuleInterface]
     */
    protected $rules;
    
    /**
     * Etapes.
     * 
     * @var Step[] string => Step : mêmes clés que les conditions
     */
    protected $steps;
    
    /**
     * Création des différentes étapes composant le workflow.
     * 
     * @return self
     */
    abstract protected function createSteps();
    
    /**
     * Forcera les étapes du workflow à être créées à nouveau.
     * 
     * @return self
     */
    public function recreateSteps()
    {
        $this->steps = null;
        $this->setCurrentStep(null);
        
        return $this;
    }
    
    /**
     * Parcourt les étapes pour déterminer l'étape courante (i.e. 1ere étape non franchissable trouvée).
     * Chaque étape est marquée selon qu'elle est "courante" ou non et/ou "franchie" ou non.
     * 
     * @return self
     */
    protected function processSteps()
    {
        if (!$this->steps) {
            $this->createSteps();
        }
        
        $currentStep = null;
        
        foreach ($this->getSteps() as $key => $step) { /* @var $step Step */
            $rule = $this->getCrossingRule($key);

            /**
             * Si l'étape n'est pas franchissable, c'est l'étape courante : 
             * elle sera déclarée "courante" et "non franchie" plus bas.
             */
            if ($rule && $rule->isRelevant() && !$rule->execute()) {
                $currentStep = $step;
            }
            /**
             * Si elle franchissable et située APRÈS l'étape courante, elle est déclarée "non courante" et "non franchie".
             */
            elseif ($currentStep) {
                $step
                        ->setIsCurrent(false)
                        ->setDone(false);
            }
            /**
             * Si elle franchissable et située AVANT l'étape courante, elle est déclarée "non courante" et "franchie".
             */
            else {
                $step
                        ->setIsCurrent(false)
                        ->setDone(true);
            }
        }
        
        /**
         * Si aucune étape courante n'est trouvée, ce sera la dernière étape qui fera office.
         */
        if (!$currentStep) {
            $currentStep = $this->getLastStep();
        }

        /**
         * Etape courante.
         */
        $currentStep
                    ->setIsCurrent(true)
                    ->setDone(false);
        
        $this->setCurrentStep($currentStep);
            
        return $this;
    }
    
    /**
     * Ajoute de règle : de pertinence et de franchissement.
     * 
     * @param string $key Clé de l'étape
     * @param RuleInterface $relevanceRule Règle de pertinence
     * @param RuleInterface $crossingRule  Règle de franchissement
     * @return self
     */
    protected function addRule($key, RuleInterface $relevanceRule = null, RuleInterface $crossingRule = null)
    {   
        $this->rules[$key] = ['relevance' => $relevanceRule, 'crossing' => $crossingRule];

        return $this;
    }
    
    /**
     * Ajoute une étape au workflow.
     * 
     * @param string $step Etape à ajouter
     * @param string $key Clé facultative de l'étape dans la liste des étapes
     * @return self
     */
    protected function addStep(Step $step, $key = null)
    {
        if (null !== $key) {
            $step->setKey($key);
        }
        $step->setIndex(count($this->getSteps()) + 1);
        
        $this->steps[$step->getKey()] = $step;

        return $this;
    }
    
    /**
     * Retourne l'étape du workflow associée à la clé spécifiée.
     * 
     * @param string $key Clé de l'étape dans la liste des étapes
     * @return Step Etape
     * @throws RuntimeException Etape introuvable
     */
    public function getStep($key)
    {
        if (!$this->containsStep($key)) {
            throw new RuntimeException("Aucune étape trouvée dans le workflow avec la clé '$key'.");
        }
        
        return $this->getSteps()[$key];
    }
    
    /**
     * Retourne toutes les étapes du workflow.
     * 
     * @return Step[] string => Step : mêmes clés que les conditions
     */
    public function getSteps()
    {
        if (null === $this->steps) {
            $this->createSteps();
        }
        
        return $this->steps;
    }
    
    /**
     * Retourne toutes les règles métiers du workflow.
     * 
     * @return array clé => ['relevance' => RuleInterface, 'crossing' => RuleInterface] : mêmes clés que les conditions
     */
    public function getRules()
    {
        if (null === $this->rules) {
            $this->createSteps();
        }
        
        return $this->rules;
    }
    
    /**
     * Retourne une règle métier de pertinence précise du workflow.
     * 
     * @param string $key Clé de l'étape
     * @return RuleInterface
     */
    protected function getRelevanceRule($key)
    {
        $rules = $this->getRules();
        
        if (!isset($rules[$key]['relevance'])) {
            return null;
        }
        
        return $rules[$key]['relevance'];
    }
    
    /**
     * Retourne une règle métier de franchissement précise du workflow.
     * 
     * @param string $key Clé de l'étape
     * @return RuleInterface
     */
    protected function getCrossingRule($key)
    {
        $rules = $this->getRules();
        
        if (!isset($rules[$key]['crossing'])) {
            return null;
        }
        
        return $rules[$key]['crossing'];
    }

    /**
     * Etape où en est l'intervenant dans le workflow.
     * 
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
     * @param Step|string|null $currentStep Etape ou clé de l'étape, ou null
     * @return self
     */
    protected function setCurrentStep($currentStep = null)
    {
        if (is_string($key = $currentStep)) {
            $currentStep = $this->getStep($key);
        }
        
        $this->currentStep = $currentStep;
    
        return $this;
    }
    
    /**
     * Teste si l'étape spécifiée est atteignable ou non.
     * C'est le cas ssi toutes les étapes la précédant sont franchissables.
     * 
     * NB: une étape n'est présente dans le workflow que si elle est pertinente, donc on 
     * peut ne tester que la règle de franchissement de chaque étape présente.
     * 
     * @param Step|string $step Etape ou clé de l'étape à atteindre
     * @return boolean <code>true</code> si celle-ci est atteignable, <code>false</code> sinon
     */
    public function isStepReachable($step)
    {
        if (is_string($step)) {
            $step = $this->getStep($step);
        }
        
        foreach ($this->getSteps() as $s) { /* @var $s Step */
            if ($s === $step) {
                return true;
            }
            if (!$s->getDone()) {
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * Teste si l'étape spécifié est franchissable ou non.
     * C'est le cas ssi l'étape est atteignable et la règle de franchissement associée est satisfaite.
     * 
     * @param Step|string $step Etape ou clé de l'étape à franchir
     * @return boolean
     */
    public function isStepCrossable($step)
    {
        if (is_string($step)) {
            $step = $this->getStep($step);
        }
        if (is_bool($step->getCrossable())) {
            return $step->getCrossable();
        }
        
//        $reachable = $this->isStepReachable($step);
//        $crossable = false;
//        
//        if ($reachable) {
//            $rule = $this->getCrossingRule($step->getKey());
//            $crossable =  !$rule || $rule->isRelevant() && $rule->execute();
//        }
//        
//        return $reachable && $crossable;
        return $step->getDone();
    }
    
    /**
     * Retourne l'étape du workflow située juste après l'étape spécifiée
     * ou juste après l'étape courante si aucune étape n'est spécifiée.
     * 
     * @param Step|string $step Etape ou clé de l'étape
     * @return Step
     */
    public function getNextStep($step = null)
    {
        if (is_string($step)) {
            $step = $this->getStep($step);
        }
        
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
            throw new LogicException("Aucune étape dans le workflow!");
        }
        
        return current($slice);
    }
    
    /**
     * Retourne l'étape correspondant à la route spécifiée.
     * 
     * @param string $route Nom de la route
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
        $application = $this->getServiceLocator()->get('Application'); /* @var $application Application */
        $route = $application->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
        
        return $this->getStepForRoute($route);
    }
    
    /**
     * Teste si le workflow contient bien l'étape spécifiée.
     * 
     * @param Step|string $step Etape ou clé de l'étape recherchée
     * @return bool
     */
    protected function containsStep($step)
    {
        if (is_string($key = $step)) {
            return isset($this->getSteps()[$key]);
        }
        
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
     * @param Step|string $step Etape ou clé de l'étape
     * @return boolean
     */
    public function isStepBeforeCurrentStep($step)
    {
        if (!$this->containsStep($step)) {
            throw new RuntimeException("Aucune étape trouvée dans le workflow avec la clé '$key'.");
        }
        if (is_string($step)) {
            $step = $this->getStep($step);
        }
        
        $currentStep = $this->getCurrentStep();
        
        if (null === $currentStep) {
            // pas d'étape courante, le workflow est terminé :
            // l'étape spécifiée est considérée comme avant l'étape courante
            /** @todo Créer peut-être une étape finale générique */
            return true; 
        }
        
        return $step->getIndex() < $currentStep->getIndex();
    }
    
    /**
     * Teste si l'étape spécifiée se situe après l'étape courante dans le workflow.
     * 
     * @param Step|string $step Etape ou clé de l'étape
     * @return boolean
     */
    public function isStepAfterCurrentStep($step)
    {
        if (!$this->containsStep($step)) {
            throw new RuntimeException("Aucune étape trouvée dans le workflow avec la clé '$key'.");
        }
        if (is_string($key = $step)) {
            $step = $this->getStep($key);
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
     * Retourne le plugin Url permettant de générer l'URL associé à une étape.
     * 
     * @return Url
     */
    protected function getHelperUrl()
    {
        return $this->getServiceLocator()->get('ControllerPluginManager')->get('Url');
    }
}