<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Interfaces\IntervenantAwareInterface;
use Application\Interfaces\StructureAwareInterface;
use Application\Interfaces\AnneeAwareInterface;
use Application\Interfaces\TypeAgrementAwareInterface;
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
        
        $etapes = $this->getServiceWfEtape()->findAll();     
        
        foreach ($etapes as $key => $etape) {
            // Règle de pertinence de l'étape
            $relevanceRule = $this->createRule($etape->getPertinRuleClass(), $key);
            // Règle de franchissement de l'étape
            $crossingRule  = $this->createRule($etape->getFranchRuleClass(), $key);
            
            // Les règles métiers de toutes les étapes existantes sont disponibles dans le WF.
            $this->addRule($key, $relevanceRule, $crossingRule);
            // Mais seules les étapes pertinentes sont ajoutées au WF.
            if (!$relevanceRule || $relevanceRule->execute()) {
                $step = $this->createStep($etape->getStepClass(), $key, $etape->getVisible());
                $this->addStep($step);
            }
        }

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
     * @return AbstractIntervenantRule
     */
    protected function createStep($stepClass, $key, $visible = true)
    {
        if (!$stepClass) {
            $stepClass = 'Application\Service\Workflow\Step\GenericStep';
        }
        
        $step = new $stepClass();
        $step
                ->setKey($key)
                ->setVisible($visible);
        
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