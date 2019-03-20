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
    private $peutSaisirDossier = false;

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
     * Get peutSaisirDossier
     *
     * @return boolean
     */
    public function getPeutSaisirDossier()
    {
        return $this->peutSaisirDossier;
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
     * Get validation
     *
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
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
     * Get dossier
     *
     * @return \Application\Entity\Db\Dossier
     */
    public function getDossier()
    {
        return $this->dossier;
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

