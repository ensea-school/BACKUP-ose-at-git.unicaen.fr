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
    /**
     * @var TblWorkflow
     */
    private $tblWorkflow;





    /**
     * @param TblWorkflow $tblWorkflow
     * @return self
     */
    public function setTblWorkflow( TblWorkflow $tblWorkflow = null )
    {
        $this->tblWorkflow = $tblWorkflow;
        return $this;
    }



    /**
     * @return TblWorkflow
     */
    public function getTblWorkflow()
    {
        return $this->tblWorkflow;
    }
}