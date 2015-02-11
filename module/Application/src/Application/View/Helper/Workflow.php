<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Entity\Db\Intervenant;
use Application\Service\Workflow\AbstractWorkflow;

/**
 * Description of Workflow
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Workflow extends AbstractHelper implements ServiceLocatorAwareInterface, WorkflowIntervenantAwareInterface
{
    use ServiceLocatorAwareTrait;
    use WorkflowIntervenantAwareTrait;

    private $wf;
    private $intervenant;
    private $role;

    /**
     * Point d'entrée.
     * 
     * @param Intervenant $intervenant
     * @param RoleInterface $role
     * @return AbstractWorkflow
     */
    public function __invoke(Intervenant $intervenant, RoleInterface $role)
    {
        $this->intervenant = $intervenant;
        $this->role        = $role;

        return $this;
    }

    /**
     * Retourne le code HTML d'un lien pointant vers l'étape courante.
     * 
     * @param string $prependText Eventuel texte à ajouter à la fin du libellé du lien
     * @return string
     */
    public function navCurrent($prependText = null)
    {
        $wf          = $this->getWorkflow(); /* @var $wf AbstractWorkflow */
        $currentStep = $wf->getCurrentStep();

        if (!$currentStep) {
            return '';
        }

        $url   = $wf->getStepUrl($currentStep);
        $label = $this->getView()->translate($currentStep->getLabel($this->role)) . '...';

        if ($prependText) {
            $label = $prependText . lcfirst($label);
        }

        return sprintf('<a href="%s" class="wf-nav-current-btn btn btn-primary">%s</a>', $url, $label);
    }

    /**
     * Retourne le code HTML d'un lien pointant vers l'étape suivante.
     * 
     * @param string $prependText Eventuel texte à ajouter à la fin du libellé du lien
     * @param bool $returnNothingIfNextStepIsDone Si ce paramètre vaut true et que 
     * l'étape suivante est franchie, rien n'est retourné
     * @return string
     */
    public function navNext($prependText = null, $returnNothingIfNextStepIsDone = true)
    {
        $wf = $this->getWorkflow(); /* @var $wf AbstractWorkflow */
        $wf->getCurrentStep();

        $currentStep = ($route = $this->getCurrentRoute()) ? $wf->getStepForRoute($route) : $wf->getStepForCurrentRoute();
        if (!$currentStep || !$currentStep->getDone()) {
            return '';
        }

        $nextStep = $wf->getNextStep($currentStep);
        if (!$nextStep) {
            return '';
        }

        if ($returnNothingIfNextStepIsDone && $nextStep->getDone()) {
            return '';
        }
        
        $url   = $wf->getStepUrl($nextStep);
        $label = $this->getView()->translate($nextStep->getLabel($this->role)) . '...';

        if ($prependText) {
            $label = $prependText . lcfirst($label);
        }

        return sprintf('<a href="%s" class="wf-nav-next-btn btn btn-primary">%s</a>', $url, $label);
    }

    /**
     * @var string
     */
    private $currentRoute;
    
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    public function setCurrentRoute($currentRoute)
    {
        $this->currentRoute = $currentRoute;
        return $this;
    }

    /**
     * Retourne l'objet Workflow utilisé par cette aide de vue.
     * 
     * @return AbstractWorkflow
     */
    public function getWorkflow()
    {
        if (null === $this->wf) {
            $this->wf = $this->getWorkflowIntervenant();
        }
        
        $this->wf
                ->setIntervenant($this->intervenant)
                ->setRole($this->role);

        return $this->wf;
    }
}