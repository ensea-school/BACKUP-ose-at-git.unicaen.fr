<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Interfaces\IntervenantAwareInterface;
use Application\Interfaces\StructureAwareInterface;
use Application\Interfaces\AnneeAwareInterface;
use Application\Interfaces\TypeAgrementAwareInterface;
use Application\Entity\Db\WfEtape;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Rule\Intervenant\AbstractIntervenantRule;
use Application\Service\WfEtape as WfEtapeService;
use Application\Service\WfIntervenantEtape as WfIntervenantEtapeService;
use Application\Service\TypeAgrement as TypeAgrementService;
use Application\Service\Workflow\Step\Step;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\StructureAwareTrait;
use Application\Traits\RoleAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Workflow extends AbstractWorkflow
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use RoleAwareTrait;
    
    const DONNEES_PERSO_SAISIE     = 'DONNEES_PERSO_SAISIE';
    const DONNEES_PERSO_VALIDATION = 'DONNEES_PERSO_VALIDATION';
    const SERVICE_SAISIE           = 'SERVICE_SAISIE';
    const SERVICE_VALIDATION       = 'SERVICE_VALIDATION';
    const REFERENTIEL_SAISIE       = 'REFERENTIEL_SAISIE';
    const REFERENTIEL_VALIDATION   = 'REFERENTIEL_VALIDATION';
    const PIECES_JOINTES           = 'PIECES_JOINTES';
    const CONSEIL_RESTREINT        = TypeAgrement::CODE_CONSEIL_RESTREINT;  // NB: c'est texto le code du type d'agrément
    const CONSEIL_ACADEMIQUE       = TypeAgrement::CODE_CONSEIL_ACADEMIQUE; // NB: c'est texto le code du type d'agrément
    const CONTRAT                  = 'CONTRAT';

    /**
     * Spécifie l'intervenant concerné.
     * 
     * NB: Cet intervenant sera injecté dans les règles métiers.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        
        $this->recreateSteps();
        
        return $this;
    }
    
    /**
     * Spécifie la structure concernée éventuelle.
     * 
     * NB: Cette structure sera injectée dans les règles métiers.
     * 
     * @param Structure $structure Structure concernée
     */
    public function setStructure(Structure $structure = null)
    {
        $this->structure = $structure;
        
        $this->recreateSteps();
        
        return $this;
    }
    
    /**
     * Spécifie le rôle courant.
     * 
     * NB: Si le rôle est liée à une structure, celle-ci remplace la structure spécifiée précédemment.
     * 
     * @param RoleInterface $role
     */
    public function setRole(RoleInterface $role)
    {
        $this->role = $role;
        
        if ($this->getRole() instanceof ComposanteRole) {
            return $this->setStructure($this->getRole()->getStructure());
        }
        
        $this->recreateSteps();
        
        return $this;
    }
    
    /**
     * Création des différentes étapes et règles métiers composant le workflow.
     * 
     * @return self
     */
    protected function createSteps()
    {        
        if (!$this->getIntervenant()) {
            throw new LogicException("Un intervenant doit être spécifié dans le workflow.");
        }
        
        $this->steps = [];
        $this->rules = [];
        
        $service = $this->getServiceWfIntervenantEtape();
        
        // Fetch de la progression de l'intervenant (création si inexistante)
        $ies = $service->findIntervenantEtape($this->getIntervenant());
        if (!count($ies)) {
            $service->createIntervenantEtapes($this->getIntervenant());
            $ies = $service->findIntervenantEtape($this->getIntervenant());
        }
        
        $dbFunctionRule = $this->getServiceLocator()->get('DbFunctionRule');
        $currentFound   = false;
        
        foreach ($ies as $ie) {
            $etape = $ie->getEtape();
            $key   = $etape->getCode();
            
            $relevanceRule = clone $dbFunctionRule;
            $relevanceRule
                    ->setFunction($etape->getPertinFunc())
                    ->setIntervenant($this->getIntervenant())
                    ->setStructure($this->getStructure());
            $crossingRule = clone $dbFunctionRule;
            $crossingRule
                    ->setFunction($etape->getFranchFunc())
                    ->setIntervenant($this->getIntervenant())
                    ->setStructure($this->getStructure());
            $this->addRule($key, $relevanceRule, $crossingRule);
            
            $isCurrent = $ie->getCourante();
            $done      = $ie->getFranchie();
            
            /**
             * Certaines étapes du workflow peuvent être "déclinées" par structure d'enseignement,
             * exemple: l'étape "validation des enseignements".
             * 
             * La progression de l'intervenant dans le wf stockée en base de données
             * ne gère pas cette déclinaison par structure : l'étape "validation des enseignements"
             * marquée "franchie" signifie que des enseignements ont bien été validés mais 
             * sans considération pour la structure d'enseignement précise.
             * 
             * Le caractère "franchie" de certaines étapes peut être réévalué pour une structure d'enseignement
             * précise. Cela est utile pour le rôle gestionnaire par exemple : ce dernier s'exerçant sur
             * une structure de responsabilité précise, on veut savoir si une étape franchie l'est bien
             * pour cette structure de responsabilité en particulier.
             */
            if ($currentFound) {
                $isCurrent = false;
                $done      = false;
            }
            if ($done && !$currentFound && $etape->getStructureDependant() && $this->getStructure()) {
                if ($crossingRule->isRelevant() && !$crossingRule->execute()) {
                    $isCurrent = true;
                    $done = false;
                    $currentFound = true;
                }
            }
            
            $step = $this->createStep($etape);
            $step
                    ->setLabel($etape->getLibelle())
                    ->setIsCurrent($isCurrent)
                    ->setDone($done);
            $this->addStep($step);
        }

        return $this;
    }
    /**
     * Parcourt les étapes pour déterminer l'étape courante (i.e. 1ere étape non franchissable trouvée).
     * 
     * @return self
     */
    protected function processSteps()
    {
        $currentStep = null;
        
        /**
         * Recherche de l'étape courante.
         */
        foreach ($this->getSteps() as $step) { /* @var $step Step */
            if ($step->getIsCurrent()) {
                $currentStep = $step;
                break;
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
        $this->setCurrentStep($currentStep);
            
        return $this;
    }
    
    /**
     * Instanciation d'une règle métier spécifiée par un nom de service ou de classe.
     * 
     * NB: les différents paramètres éventuellement spécifiés sont injectés dans la règle
     * (intervenant, structure, année, etc.)
     * 
     * @param string $ruleClassName
     * @param string $key
     * @return AbstractIntervenantRule
     */
    protected function createRule($ruleClassName, $key)
    {
        if (!$ruleClassName) {
            return null;
        }
        
        $rule = null;
        
        // tentative via le gestionnaire de service
        if (($alias = trim(strrchr($ruleClassName, '\\'), '\\'))) {
            if ($this->getServiceLocator()->has($alias)) {
                $rule = clone $this->getServiceLocator()->get($alias);
            }
        }
        // sinon instanciation classique
        if (null === $rule) {
            $rule = new $ruleClassName();
        }
        
        // injection de l'intervenant
        if ($rule instanceof IntervenantAwareInterface) {
            $rule->setIntervenant($this->getIntervenant());
        }
        
        // injection éventuelle de la structure
        if ($rule instanceof StructureAwareInterface) {
            $rule->setStructure($this->getStructure());
        }
        
        // injection éventuelle de l'année
        if ($rule instanceof AnneeAwareInterface) {
            $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
            $rule->setAnnee($annee);
        }
        
        // injection éventuelle du type d'agrément
        if ($rule instanceof TypeAgrementAwareInterface) {
            $typeAgrement = $this->getServiceTypeAgrement()->getRepo()->findOneByCode($key);
            $rule->setTypeAgrement($typeAgrement);
        }
        
        return $rule;
    }
    
    /**
     * Instanciation d'une étape de WF spécifiée par le nom de sa classe.
     * 
     * @param string $stepClass Classe à instancier
     * @param string $key Clé de l'étape.
     * @param boolean $visible Témoin de visibilité de l'étape
     * @return Step
     */
//    protected function createStep($stepClass, $key, $visible = true)
//    {
//        if (!$stepClass) {
//            $stepClass = 'Application\Service\Workflow\Step\GenericStep';
//        }
//        
//        $step = new $stepClass();
//        $step
//                ->setKey($key)
//                ->setVisible($visible);
//        
//        if ($step instanceof TypeAgrementAwareInterface) {
//            $typeAgrement = $this->getServiceTypeAgrement()->getRepo()->findOneByCode($key);
//            $step->setTypeAgrement($typeAgrement);
//        }
//        
//        return $step;
//    }
    protected function createStep(WfEtape $wfEtape)
    {
        $stepClass = $wfEtape->getStepClass() ? : 'Application\Service\Workflow\Step\GenericStep';
        $key       = $wfEtape->getCode();
        $visible   = $wfEtape->getVisible();

        $step = new $stepClass();
        $step
                ->setKey($key)
                ->setVisible($visible)
                ->setWfEtape($wfEtape);
        
        if ($step instanceof TypeAgrementAwareInterface) {
            $typeAgrement = $this->getServiceTypeAgrement()->getRepo()->findOneByCode($key);
            $step->setTypeAgrement($typeAgrement);
        }
        
        return $step;
    }
    
    /**
     * Retourne l'URL correspondant à l'étape spécifiée.
     * 
     * @param Step $step
     * @param Intervenant $intervenant
     * @return string
     */
    public function getStepUrl(Step $step, Intervenant $intervenant = null)
    {
        if (null === $intervenant) {
            $intervenant = $this->getIntervenant();
        }
        
        $params = array_merge(
                $step->getRouteParams(), 
                array('intervenant' => $intervenant->getSourceCode()));
        
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

    /**
     * 
     * @return WfEtapeService
     */
    private function getServiceWfEtape()
    {
        return $this->getServiceLocator()->get('WfEtapeService');
    } 

    /**
     * 
     * @return WfIntervenantEtapeService
     */
    private function getServiceWfIntervenantEtape()
    {
        return $this->getServiceLocator()->get('WfIntervenantEtapeService');
    } 
    
    /**
     * 
     * @return TypeAgrementService
     */
    private function getServiceTypeAgrement()
    {
        return $this->getServiceLocator()->get('ApplicationTypeAgrement');
    }
}