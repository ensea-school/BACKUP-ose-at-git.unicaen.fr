<?php

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Traits\WorkflowIntervenantAwareTrait;
use Application\Entity\Db\Intervenant;
use Application\Service\Workflow\AbstractWorkflow;

/**
 * Description of Workflow
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Workflow extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use WorkflowIntervenantAwareTrait;

    private $wf;
    private $intervenant;
    private $role;

    /**
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
     * 
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
     * 
     * @return string
     */
    public function navNext($prependText = null)
    {
        $wf = $this->getWorkflow(); /* @var $wf AbstractWorkflow */

        $step = ($route = $this->getCurrentRoute()) ? $wf->getStepForRoute($route) : $wf->getStepForCurrentRoute();
        if (!$step || !$step->getDone()) {
            return '';
        }

        $nextStep = $wf->getNextStep($step);
        if (!$nextStep) {
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
     * 
     * @return AbstractWorkflow
     */
    public function getWorkflow()
    {
        if (null === $this->wf) {
            $this->wf = $this->getWorkflowIntervenant($this->intervenant, $this->getServiceLocator()->getServiceLocator());
        }

        return $this->wf;
    }
}