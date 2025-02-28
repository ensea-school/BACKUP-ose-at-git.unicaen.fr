<?php

namespace Workflow\Entity\Db;

/**
 * TblWorkflow
 */
class TblWorkflow
{
    /**
     * @var boolean
     */
    private $atteignable = true;

    /**
     * @var float
     */
    private $objectif = 0;

    /**
     * @var float
     */
    private $realisation = 0;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lieu\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Intervenant\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Workflow\Entity\Db\WfEtape
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
     * Get atteignable
     *
     * @return boolean
     */
    public function getAtteignable()
    {
        return $this->atteignable;
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
     * Get realisation
     *
     * @return float
     */
    public function getRealisation()
    {
        return $this->realisation;
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
     * Get structure
     *
     * @return \Lieu\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Get intervenant
     *
     * @return \Intervenant\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Get etape
     *
     * @return \Workflow\Entity\Db\WfEtape
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
        if ($this->objectif > 0) {
            $res = $this->realisation / $this->objectif;
        }
        if ($res > 1) $res = 1; // pour éviter tout malentendu au cas où...
        return $res;
    }
}

