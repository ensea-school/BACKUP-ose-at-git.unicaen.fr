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
    /**
     * @var WorkflowService
     */
    private $serviceWorkflow;



    /**
     * @param WorkflowService $serviceWorkflow
     *
     * @return self
     */
    public function setServiceWorkflow(WorkflowService $serviceWorkflow)
    {
        $this->serviceWorkflow = $serviceWorkflow;

        return $this;
    }



    /**
     * @return WorkflowService
     */
    public function getServiceWorkflow()
    {
        if (empty($this->serviceWorkflow)) {
            $this->serviceWorkflow = \Application::$container->get(WorkflowService::class);
        }

        return $this->serviceWorkflow;
    }
}