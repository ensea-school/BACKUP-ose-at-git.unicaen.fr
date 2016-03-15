<?php

namespace Application\Entity\Db;

/**
 * TblContrat
 */
class TblContrat
{
    /**
     * @var float
     */
    private $edite = '0';

    /**
     * @var float
     */
    private $nbvh = '0';

    /**
     * @var boolean
     */
    private $peutAvoirContrat = '0';

    /**
     * @var float
     */
    private $signe = '0';

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
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set edite
     *
     * @param float $edite
     *
     * @return TblContrat
     */
    public function setEdite($edite)
    {
        $this->edite = $edite;

        return $this;
    }

    /**
     * Get edite
     *
     * @return float
     */
    public function getEdite()
    {
        return $this->edite;
    }

    /**
     * Set nbvh
     *
     * @param float $nbvh
     *
     * @return TblContrat
     */
    public function setNbvh($nbvh)
    {
        $this->nbvh = $nbvh;

        return $this;
    }

    /**
     * Get nbvh
     *
     * @return float
     */
    public function getNbvh()
    {
        return $this->nbvh;
    }

    /**
     * Set peutAvoirContrat
     *
     * @param boolean $peutAvoirContrat
     *
     * @return TblContrat
     */
    public function setPeutAvoirContrat($peutAvoirContrat)
    {
        $this->peutAvoirContrat = $peutAvoirContrat;

        return $this;
    }

    /**
     * Get peutAvoirContrat
     *
     * @return boolean
     */
    public function getPeutAvoirContrat()
    {
        return $this->peutAvoirContrat;
    }

    /**
     * Set signe
     *
     * @param float $signe
     *
     * @return TblContrat
     */
    public function setSigne($signe)
    {
        $this->signe = $signe;

        return $this;
    }

    /**
     * Get signe
     *
     * @return float
     */
    public function getSigne()
    {
        return $this->signe;
    }

    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblContrat
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
     * @return TblContrat
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
     * @return TblContrat
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
     * @return TblContrat
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

