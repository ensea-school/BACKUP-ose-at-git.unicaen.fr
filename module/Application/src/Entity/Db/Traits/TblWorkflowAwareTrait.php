<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblWorkflow;

/**
 * Description of TblWorkflowAwareTrait
 *
 * @author UnicaenCode
 */
trait TblWorkflowAwareTrait
{
    protected ?TblWorkflow $tblWorkflow = null;



    /**
     * @param TblWorkflow $tblWorkflow
     *
     * @return self
     */
    public function setTblWorkflow( ?TblWorkflow $tblWorkflow )
    {
        $this->tblWorkflow = $tblWorkflow;

        return $this;
    }



    public function getTblWorkflow(): ?TblWorkflow
    {
        return $this->tblWorkflow;
    }
}