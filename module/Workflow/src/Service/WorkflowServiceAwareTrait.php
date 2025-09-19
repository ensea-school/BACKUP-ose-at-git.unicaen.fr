<?php

namespace Workflow\Service;

/**
 * Description of WorkflowServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait WorkflowServiceAwareTrait
{
    protected ?WorkflowService $serviceWorkflow = null;



    /**
     * @param WorkflowService $serviceWorkflow
     *
     * @return self
     */
    public function setServiceWorkflow(?WorkflowService $serviceWorkflow)
    {
        $this->serviceWorkflow = $serviceWorkflow;

        return $this;
    }



    public function getServiceWorkflow(): ?WorkflowService
    {
        if (empty($this->serviceWorkflow)) {
            $this->serviceWorkflow = \Framework\Application\Application::getInstance()->container()->get(WorkflowService::class);
        }

        return $this->serviceWorkflow;
    }
}