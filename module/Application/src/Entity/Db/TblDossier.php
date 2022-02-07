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
    private $dossier = false;

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
     * @var \Application\Entity\Db\IntervenantDossier
     */
    private $dossier;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var boolean
     */
    protected $completudeIdentite;

    /**
     * @var boolean
     */
    protected $completudeIdentiteComp;

    /**
     * @var boolean
     */
    protected $completudeContact;

    /**
     * @var boolean
     */
    protected $completudeAdresse;

    /**
     * @var boolean
     */
    protected $completudeInsee;

    /**
     * @var boolean
     */
    protected $completudeIban;

    /**
     * @var boolean
     */
    protected $completudeEmployeur;

    /**
     * @var boolean
     */
    protected $completudeAutres;

    /**
     * @var boolean
     */
    protected $completudeStatut;



    /**
     * Get dossier
     *
     * @return boolean
     */
    public function getDossier(): bool
    {
        return $this->dossier;
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
     * @return \Application\Entity\Db\IntervenantDossier
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



    /**
     * Get complétude identité
     *
     * @return boolean
     */

    public function getCompletudeIdentite()
    {
        return $this->completudeIdentite;
    }



    /**
     * Get complétude identité complementaire
     *
     * @return boolean
     */

    public function getCompletudeIdentiteComp()
    {
        return $this->completudeIdentiteComp;
    }



    /**
     * Get complétude contact
     *
     * @return boolean
     */

    public function getCompletudeContact()
    {
        return $this->completudeContact;
    }



    /**
     * Get complétude adresse
     *
     * @return boolean
     */

    public function getCompletudeAdresse()
    {
        return $this->completudeAdresse;
    }



    /**
     * Get complétude INSEE
     *
     * @return boolean
     */

    public function getCompletudeInsee()
    {
        return $this->completudeInsee;
    }



    /**
     * Get complétude iban
     *
     * @return boolean
     */

    public function getCompletudeIban()
    {
        return $this->completudeIban;
    }



    /**
     * Get complétude employeur
     *
     * @return boolean
     */

    public function getCompletudeEmployeur()
    {
        return $this->completudeEmployeur;
    }



    /**
     * Get complétude autres
     *
     * @return boolean
     */

    public function getCompletudeAutres()
    {
        return $this->completudeAutres;
    }



    /**
     * Get complétude statut
     *
     * @return boolean
     */

    public function getCompletudeStatut()
    {
        return $this->completudeStatut;
    }



    public function getCompletude(): bool
    {
        return $this->getCompletudeIdentite() &&
            $this->getCompletudeIdentiteComp() &&
            $this->getCompletudeAdresse() &&
            $this->getCompletudeContact() &&
            $this->getCompletudeInsee() &&
            $this->getCompletudeIban() &&
            $this->getCompletudeEmployeur() &&
            $this->getCompletudeAutres() &&
            $this->getCompletudeStatut();
    }
}

