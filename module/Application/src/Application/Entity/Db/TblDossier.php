<?php

namespace Application\Entity\Db;

/**
 * TblDossier
 */
class TblDossier
{
    /**
     * @var boolean
     */
    private $peutSaisirDossier = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Dossier
     */
    private $dossier;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set peutSaisirDossier
     *
     * @param boolean $peutSaisirDossier
     *
     * @return TblDossier
     */
    public function setPeutSaisirDossier($peutSaisirDossier)
    {
        $this->peutSaisirDossier = $peutSaisirDossier;

        return $this;
    }

    /**
     * Get peutSaisirDossier
     *
     * @return boolean
     */
    public function getPeutSaisirDossier()
    {
        return $this->peutSaisirDossier;
    }

    /**
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblDossier
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
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return TblDossier
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return TblDossier
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
     * Set dossier
     *
     * @param \Application\Entity\Db\Dossier $dossier
     *
     * @return TblDossier
     */
    public function setDossier(\Application\Entity\Db\Dossier $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \Application\Entity\Db\Dossier
     */
    public function getDossier()
    {
        return $this->dossier;
    }

    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblDossier
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

