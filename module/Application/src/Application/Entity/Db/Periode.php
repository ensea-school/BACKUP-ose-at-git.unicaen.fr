<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periode
 */
class Periode
{
    const SEMESTRE_1 = 'S1';
    const SEMESTRE_2 = 'S2';

    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var \DateTime
     */
    protected $histoModification;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

	/**
     * @var boolean
     */
    protected $enseignement;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var boolean
     */
    protected $paiement;

    /**
     * moisOriginePaiement
     *
     * @var integer
     */
    protected $moisOriginePaiement;

    /**
     * numeroMoisPaiement
     *
     * @var integer
     */
    protected $numeroMoisPaiement;



        /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;



    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Periode
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return Periode
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return Periode
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Periode
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Periode
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return Periode
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Periode
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

	/**
     * Set enseignement
     *
     * @param boolean $enseignement
     * @return Periode
     */
    public function setEnseignement($enseignement)
    {
        $this->enseignement = $enseignement;

        return $this;
    }

    /**
     * Get enseignement
     *
     * @return boolean 
     */
    public function getEnseignement()
    {
        return $this->enseignement;
    }

    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     * @return Periode
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }

    /**
     * Get libelleCourt
     *
     * @return string 
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     * @return Periode
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }

    /**
     * Get libelleLong
     *
     * @return string 
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * Set paiement
     *
     * @param boolean $paiement
     * @return Periode
     */
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return boolean 
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * Set moisOriginePaiement
     *
     * @param boolean $moisOriginePaiement
     * @return Periode
     */
    public function setMoisOriginePaiement($moisOriginePaiement)
    {
        $this->moisOriginePaiement = $moisOriginePaiement;

        return $this;
    }

    /**
     * Get moisOriginePaiement
     *
     * @return boolean
     */
    public function getMoisOriginePaiement()
    {
        return $this->moisOriginePaiement;
    }

    /**
     * Set numeroMoisPaiement
     *
     * @param boolean $numeroMoisPaiement
     * @return Periode
     */
    public function setNumeroMoisPaiement($numeroMoisPaiement)
    {
        $this->numeroMoisPaiement = $numeroMoisPaiement;

        return $this;
    }

    /**
     * Get numeroMoisPaiement
     *
     * @return boolean
     */
    public function getNumeroMoisPaiement()
    {
        return $this->numeroMoisPaiement;
    }

    /**
     * Retourne la date de paiement de la période
     *
     * @param Annee $annee
     * @return \DateTime
     */
    public function getDatePaiement( Annee $annee )
    {
        if (null == $this->getNumeroMoisPaiement()) return null;
        $year = $annee->getId();
        $month = $this->getNumeroMoisPaiement();
        $day = 1;
        if ($month < 9) $year++;
        $a_date = date("Y-m-t", mktime(0, 0, 0, $month, $day, $year));
        $date = \DateTime::createFromFormat('Y-m-d', $a_date);
        return $date;
    }

    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleLong();
    }
}
