<?php

namespace Application\Traits;

use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\Workflow\WorkflowIntervenant;
use Application\Entity\Db\Intervenant;

/**
 * Description of WorkflowIntervenantAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait WorkflowIntervenantAwareTrait
{
    /**
     * @var WorkflowIntervenant
     */
    protected $workflowIntervenant;
    
    /**
     * Retourne le workflow IntervenantPermanent ou IntervenantExterieur.
     * 
     * @return WorkflowIntervenant
     */
    public function getWorkflowIntervenant(Intervenant $intervenant, ServiceLocatorInterface $serviceLocator = null)
    {
        if (null === $this->workflowIntervenant) {
            if (null === $serviceLocator && method_exists($this, 'getServiceLocator')) {
                $serviceLocator = $this->getServiceLocator();
            }
            if (null === $serviceLocator || !$serviceLocator instanceof ServiceLocatorInterface) {
                throw new \Common\Exception\LogicException("Aucun service locator valide disponible pour obtenir le workflow.");
            }
            $class = ltrim(strrchr(get_class($intervenant), '\\'), '\\');
            $this->workflowIntervenant = $serviceLocator->get("ApplicationWorkflow$class");
        }
        
        $this->workflowIntervenant->setIntervenant($intervenant);
        
        return $this->workflowIntervenant;
    }
}