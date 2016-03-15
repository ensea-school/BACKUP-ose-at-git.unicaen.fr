<?php

namespace Application\Entity\Db;

/**
 * TblPaiement
 */
class TblPaiement
{
    /**
     * @var integer
     */
    private $serviceAPayerId;

    /**
     * @var string
     */
    private $serviceAPayerType;

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
     * @var \Application\Entity\Db\Periode
     */
    private $periodePaiement;

    /**
     * @var \Application\Entity\Db\MiseEnPaiement
     */
    private $miseEnPaiement;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set serviceAPayerId
     *
     * @param integer $serviceAPayerId
     *
     * @return TblPaiement
     */
    public function setServiceAPayerId($serviceAPayerId)
    {
        $this->serviceAPayerId = $serviceAPayerId;

        return $this;
    }

    /**
     * Get serviceAPayerId
     *
     * @return integer
     */
    public function getServiceAPayerId()
    {
        return $this->serviceAPayerId;
    }

    /**
     * Set serviceAPayerType
     *
     * @param string $serviceAPayerType
     *
     * @return TblPaiement
     */
    public function setServiceAPayerType($serviceAPayerType)
    {
        $this->serviceAPayerType = $serviceAPayerType;

        return $this;
    }

    /**
     * Get serviceAPayerType
     *
     * @return string
     */
    public function getServiceAPayerType()
    {
        return $this->serviceAPayerType;
    }

    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblPaiement
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
     * @return TblPaiement
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
     * Set periodePaiement
     *
     * @param \Application\Entity\Db\Periode $periodePaiement
     *
     * @return TblPaiement
     */
    public function setPeriodePaiement(\Application\Entity\Db\Periode $periodePaiement = null)
    {
        $this->periodePaiement = $periodePaiement;

        return $this;
    }

    /**
     * Get periodePaiement
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriodePaiement()
    {
        return $this->periodePaiement;
    }

    /**
     * Set miseEnPaiement
     *
     * @param \Application\Entity\Db\MiseEnPaiement $miseEnPaiement
     *
     * @return TblPaiement
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
     *
     * @return TblPaiement
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblPaiement
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}

