<?php

namespace Workflow\Tbl\Process;


/**
 * Description of WorkflowProcessAwareTrait
 *
 * @author UnicaenCode
 */
trait WorkflowProcessAwareTrait
{
    protected ?WorkflowProcess $tblProcessWorkflowProcess = null;



    /**
     * @param WorkflowProcess $tblProcessWorkflowProcess
     *
     * @return self
     */
    public function setTblProcessWorkflowProcess( ?WorkflowProcess $tblProcessWorkflowProcess )
    {
        $this->tblProcessWorkflowProcess = $tblProcessWorkflowProcess;

        return $this;
    }



    public function getTblProcessWorkflowProcess(): ?WorkflowProcess
    {
        return $this->tblProcessWorkflowProcess;
    }
}