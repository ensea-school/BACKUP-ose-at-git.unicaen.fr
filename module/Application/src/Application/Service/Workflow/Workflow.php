<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Interfaces\TypeAgrementAwareInterface;
use Application\Entity\Db\WfEtape;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Service\Traits\TypeAgrementAwareTrait;
use Application\Service\WfIntervenantEtape as WfIntervenantEtapeService;
use Application\Service\Workflow\Step\Step;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\RoleAwareTrait;
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
    use TypeAgrementAwareTrait;

    const DONNEES_PERSO_SAISIE           = 'DONNEES_PERSO_SAISIE';
    const DONNEES_PERSO_VALIDATION       = 'DONNEES_PERSO_VALIDATION';

    const SERVICE_SAISIE                 = 'SERVICE_SAISIE';
    const SERVICE_SAISIE_REALISE         = 'SERVICE_SAISIE_REALISE';
    const SERVICE_VALIDATION             = 'SERVICE_VALIDATION';
    const SERVICE_VALIDATION_REALISE     = 'SERVICE_VALIDATION_REALISE';

    const REFERENTIEL_SAISIE             = 'REFERENTIEL_SAISIE';
    const REFERENTIEL_SAISIE_REALISE     = 'REFERENTIEL_SAISIE_REALISE';
    const REFERENTIEL_VALIDATION         = 'REFERENTIEL_VALIDATION';
    const REFERENTIEL_VALIDATION_REALISE = 'REFERENTIEL_VALIDATION_REALISE';

    const CLOTURE_REALISE                = 'CLOTURE_REALISE';
    
    const PIECES_JOINTES                 = 'PIECES_JOINTES';

    const CONSEIL_RESTREINT              = TypeAgrement::CODE_CONSEIL_RESTREINT;  // NB: c'est texto le code du type d'agrément
    const CONSEIL_ACADEMIQUE             = TypeAgrement::CODE_CONSEIL_ACADEMIQUE; // NB: c'est texto le code du type d'agrément

    const CONTRAT                        = 'CONTRAT';

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

        $service = $this->getServiceWfIntervenantEtape();

        // Fetch de la progression de l'intervenant, pour la structure courante éventuelle
        $ies = $service->findIntervenantEtape($this->getIntervenant()/*, $this->getStructure()*/);
        if (!count($ies)) {
            $ies = $service->findIntervenantEtape($this->getIntervenant());
        }

//        $dbFunctionRule = $this->getServiceLocator()->get('DbFunctionRule');

        foreach ($ies as $ie) {
            $etape     = $ie->getEtape();
            $isCurrent = $ie->getCourante();
            $done      = $ie->getFranchie();

            $step = $this->createStep($etape);
            $step
                    ->setIsCurrent($isCurrent)
                    ->setDone($done);
            
            $this->addStep($step);
        }

        return $this;
    }

    /**
     * Parcourt les étapes pour déterminer l'étape courante.
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
     * Instanciation de l'étape de WF spécifiée.
     *
     * @param WfEtape $wfEtape étape
     * @return Step
     */
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
                ['intervenant' => $intervenant->getSourceCode()]);

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
     * @return WfIntervenantEtapeService
     */
    private function getServiceWfIntervenantEtape()
    {
        return $this->getServiceLocator()->get('WfIntervenantEtapeService');
    }
}