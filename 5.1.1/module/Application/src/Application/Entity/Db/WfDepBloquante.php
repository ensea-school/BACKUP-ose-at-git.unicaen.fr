<?php

namespace Application\Entity\Db;

/**
 * Grade
 */
class WfDepBloquante
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var WfEtapeDep
     */
    protected $wfEtapeDep;

    /**
     * @var TblWorkflow
     */
    protected $tblWorkflow;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return WfEtapeDep
     */
    public function getWfEtapeDep()
    {
        return $this->wfEtapeDep;
    }



    /**
     * @return TblWorkflow
     */
    public function getTblWorkflow()
    {
        return $this->tblWorkflow;
    }

}
