<?php

namespace Application\Entity\Db;

/**
 * TblWorkflow
 */
class TblWorkflow
{
    /**
     * @var boolean
     */
    private $atteignable = '1';

    /**
     * @var float
     */
    private $objectif = '1';

    /**
     * @var float
     */
    private $realisation = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\WfEtape
     */
    private $etape;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etapeDeps;



    public function __construct()
    {
        $this->etapeDeps = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Set atteignable
     *
     * @param boolean $atteignable
     *
     * @return TblWorkflow
     */
    public function setAtteignable($atteignable)
    {
        $this->atteignable = $atteignable;

        return $this;
    }



    /**
     * Get atteignable
     *
     * @return boolean
     */
    public function getAtteignable()
    {
        return $this->atteignable;
    }



    /**
     * Set objectif
     *
     * @param float $objectif
     *
     * @return TblWorkflow
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }



    /**
     * Get objectif
     *
     * @return float
     */
    public function getObjectif()
    {
        return $this->objectif;
    }



    /**
     * Set realisation
     *
     * @param float $realisation
     *
     * @return TblWorkflow
     */
    public function setRealisation($realisation)
    {
        $this->realisation = $realisation;

        return $this;
    }



    /**
     * Get realisation
     *
     * @return float
     */
    public function getRealisation()
    {
        return $this->realisation;
    }



    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblWorkflow
     */
    public function setToDelete($toDelete)
    {
        $this->toDelete = $toDelete;

        return $this;
    }



    /**
     * Get toDelete
     *
     * @return boolean
     */
    public function getToDelete()
    {
        return $this->toDelete;
    }



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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return TblWorkflow
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return TblWorkflow
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set etape
     *
     * @param \Application\Entity\Db\WfEtape $etape
     *
     * @return TblWorkflow
     */
    public function setEtape(\Application\Entity\Db\WfEtape $etape = null)
    {
        $this->etape = $etape;

        return $this;
    }



    /**
     * Get etape
     *
     * @return \Application\Entity\Db\WfEtape
     */
    public function getEtape()
    {
        return $this->etape;
    }



    /**
     * Get affectation
     *
     * @return WfDepBloquante[]
     */
    public function getEtapeDeps()
    {
        return $this->etapeDeps;
    }



    /**
     * Get franchie
     *
     * @return float
     */
    public function getFranchie()
    {
        $res = 0;
        if ($this->objectif > 0){
            $res = $this->realisation / $this->objectif;
        }
        if ($res > 1) $res = 1; // pour éviter tout malentendu au cas où...
        return $res;
    }
}

