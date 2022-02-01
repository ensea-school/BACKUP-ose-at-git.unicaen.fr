<?php

namespace Application\Service\Traits;

use Application\Service\WorkflowService;

/**
 * Description of WorkflowServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait WorkflowServiceAwareTrait
{
    protected ?WorkflowService $serviceWorkflow;



    /**
     * @param WorkflowService|null $serviceWorkflow
     *
     * @return self
     */
    public function setServiceWorkflow( ?WorkflowService $serviceWorkflow )
    {
        $this->serviceWorkflow = $serviceWorkflow;

        return $this;
    }



    public function getServiceWorkflow(): ?WorkflowService
    {
        if (!$this->serviceWorkflow){
            $this->serviceWorkflow = \Application::$container->get(WorkflowService::class);
        }

        return $this->serviceWorkflow;
    }
}