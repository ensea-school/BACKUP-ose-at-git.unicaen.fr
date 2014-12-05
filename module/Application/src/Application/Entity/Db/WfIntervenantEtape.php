<?php

namespace Application\Entity\Db;

/**
 * WfIntervenantEtape
 */
class WfIntervenantEtape
{
    /**
     * @var boolean
     */
    private $franchie = false;
    
    /**
     * @var boolean
     */
    private $courante = false;

    /**
     * @var \DateTime
     */
    private $dateEntree;

    /**
     * @var \DateTime
     */
    private $dateSortie;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\WfEtape
     */
    private $etape;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * Set franchie
     *
     * @param boolean $franchie
     * @return WfIntervenantEtape
     */
    public function setFranchie($franchie)
    {
        $this->franchie = $franchie;

        return $this;
    }

    /**
     * Get franchie
     *
     * @return boolean 
     */
    public function getFranchie()
    {
        return $this->franchie;
    }

    /**
     * Set courante
     *
     * @param boolean $courante
     * @return WfIntervenantEtape
     */
    public function setCourante($courante)
    {
        $this->courante = $courante;

        return $this;
    }

    /**
     * Get courante
     *
     * @return boolean 
     */
    public function getCourante()
    {
        return $this->courante;
    }

    /**
     * Set dateEntree
     *
     * @param \DateTime $dateEntree
     * @return WfIntervenantEtape
     */
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    /**
     * Get dateEntree
     *
     * @return \DateTime 
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * Set dateSortie
     *
     * @param \DateTime $dateSortie
     * @return WfIntervenantEtape
     */
    public function setDateSortie($dateSortie)
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * Get dateSortie
     *
     * @return \DateTime 
     */
    public function getDateSortie()
    {
        return $this->dateSortie;
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
     * Set ordre
     *
     * @param integer $ordre
     * @return WfEtape
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return WfIntervenantEtape
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
     * @return WfIntervenantEtape
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return WfIntervenantEtape
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
}
