<?php

namespace Application\Service\Traits;

use Application\Service\WorkflowService;
use Application\Module;
use RuntimeException;

/**
 * Description of WorkflowServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait WorkflowServiceAwareTrait
{
    /**
     * @var WorkflowService
     */
    private $serviceWorkflow;





    /**
     * @param WorkflowService $serviceWorkflow
     * @return self
     */
    public function setServiceWorkflow( WorkflowService $serviceWorkflow )
    {
        $this->serviceWorkflow = $serviceWorkflow;
        return $this;
    }



    /**
     * @return WorkflowService
     * @throws RuntimeException
     */
    public function getServiceWorkflow()
    {
        if (empty($this->serviceWorkflow)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceWorkflow = $serviceLocator->get('workflow');
        }
        return $this->serviceWorkflow;
    }
}