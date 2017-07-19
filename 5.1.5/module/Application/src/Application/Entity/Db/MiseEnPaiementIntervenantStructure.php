<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * MiseEnPaiementIntervenantStructure
 */
class MiseEnPaiementIntervenantStructure
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\MiseEnPaiement
     */
    private $miseEnPaiement;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Periode
     */
    private $periode;


    /**
     * Set id
     *
     * @param integer $id
     * @return MiseEnPaiementIntervenantStructure
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     * @return MiseEnPaiementIntervenantStructure
     */
    public function setMiseEnPaiement(\Application\Entity\Db\MiseEnPaiement $miseEnPaiement = null)
    {
        $this->miseEnPaiement = $miseEnPaiement;

        return $this;
    }

    /**
     * Get miseEnPaiement
     *
     * @return \Application\Entity\Db\MiseEnPaiement 
     */
    public function getMiseEnPaiement()
    {
        return $this->miseEnPaiement;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return MiseEnPaiementIntervenantStructure
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return MiseEnPaiementIntervenantStructure
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
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return MiseEnPaiementIntervenantStructure
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }
}
