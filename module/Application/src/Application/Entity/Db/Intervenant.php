<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Intervenant
 */
abstract class Intervenant
{
    /**
     * @var \DateTime
     */
    private $dateNaissance;

    /**
     * @var string
     */
    private $depNaissanceCodeInsee;

    /**
     * @var string
     */
    private $depNaissanceLibelle;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $nomPatronymique;

    /**
     * @var string
     */
    private $nomUsuel;

    /**
     * @var string
     */
    private $numeroInsee;

    /**
     * @var string
     */
    private $numeroInseeCle;

    /**
     * @var string
     */
    private $numeroInseeProvisoire;

    /**
     * @var string
     */
    private $paysNaissanceCodeInsee;

    /**
     * @var string
     */
    private $paysNaissanceLibelle;

    /**
     * @var string
     */
    private $paysNationaliteCodeInsee;

    /**
     * @var string
     */
    private $paysNationaliteLibelle;

    /**
     * @var integer
     */
    private $personnelId;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $primeExcellenceScientifique;

    /**
     * @var string
     */
    private $telMobile;

    /**
     * @var string
     */
    private $villeNaissanceCodeInsee;

    /**
     * @var string
     */
    private $villeNaissanceLibelle;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\TypeIntervenant
     */
    private $type;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Civilite
     */
    private $civilite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $annee;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sectionCnu;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->annee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sectionCnu = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Intervenant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set depNaissanceCodeInsee
     *
     * @param string $depNaissanceCodeInsee
     * @return Intervenant
     */
    public function setDepNaissanceCodeInsee($depNaissanceCodeInsee)
    {
        $this->depNaissanceCodeInsee = $depNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get depNaissanceCodeInsee
     *
     * @return string 
     */
    public function getDepNaissanceCodeInsee()
    {
        return $this->depNaissanceCodeInsee;
    }

    /**
     * Set depNaissanceLibelle
     *
     * @param string $depNaissanceLibelle
     * @return Intervenant
     */
    public function setDepNaissanceLibelle($depNaissanceLibelle)
    {
        $this->depNaissanceLibelle = $depNaissanceLibelle;

        return $this;
    }

    /**
     * Get depNaissanceLibelle
     *
     * @return string 
     */
    public function getDepNaissanceLibelle()
    {
        return $this->depNaissanceLibelle;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Intervenant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nomPatronymique
     *
     * @param string $nomPatronymique
     * @return Intervenant
     */
    public function setNomPatronymique($nomPatronymique)
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }

    /**
     * Get nomPatronymique
     *
     * @return string 
     */
    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }

    /**
     * Set nomUsuel
     *
     * @param string $nomUsuel
     * @return Intervenant
     */
    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }

    /**
     * Get nomUsuel
     *
     * @return string 
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }

    /**
     * Set numeroInsee
     *
     * @param string $numeroInsee
     * @return Intervenant
     */
    public function setNumeroInsee($numeroInsee)
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }

    /**
     * Get numeroInsee
     *
     * @return string 
     */
    public function getNumeroInsee()
    {
        return $this->numeroInsee;
    }

    /**
     * Set numeroInseeCle
     *
     * @param string $numeroInseeCle
     * @return Intervenant
     */
    public function setNumeroInseeCle($numeroInseeCle)
    {
        $this->numeroInseeCle = $numeroInseeCle;

        return $this;
    }

    /**
     * Get numeroInseeCle
     *
     * @return string 
     */
    public function getNumeroInseeCle()
    {
        return $this->numeroInseeCle;
    }

    /**
     * Set numeroInseeProvisoire
     *
     * @param string $numeroInseeProvisoire
     * @return Intervenant
     */
    public function setNumeroInseeProvisoire($numeroInseeProvisoire)
    {
        $this->numeroInseeProvisoire = $numeroInseeProvisoire;

        return $this;
    }

    /**
     * Get numeroInseeProvisoire
     *
     * @return string 
     */
    public function getNumeroInseeProvisoire()
    {
        return $this->numeroInseeProvisoire;
    }

    /**
     * Set paysNaissanceCodeInsee
     *
     * @param string $paysNaissanceCodeInsee
     * @return Intervenant
     */
    public function setPaysNaissanceCodeInsee($paysNaissanceCodeInsee)
    {
        $this->paysNaissanceCodeInsee = $paysNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get paysNaissanceCodeInsee
     *
     * @return string 
     */
    public function getPaysNaissanceCodeInsee()
    {
        return $this->paysNaissanceCodeInsee;
    }

    /**
     * Set paysNaissanceLibelle
     *
     * @param string $paysNaissanceLibelle
     * @return Intervenant
     */
    public function setPaysNaissanceLibelle($paysNaissanceLibelle)
    {
        $this->paysNaissanceLibelle = $paysNaissanceLibelle;

        return $this;
    }

    /**
     * Get paysNaissanceLibelle
     *
     * @return string 
     */
    public function getPaysNaissanceLibelle()
    {
        return $this->paysNaissanceLibelle;
    }

    /**
     * Set paysNationaliteCodeInsee
     *
     * @param string $paysNationaliteCodeInsee
     * @return Intervenant
     */
    public function setPaysNationaliteCodeInsee($paysNationaliteCodeInsee)
    {
        $this->paysNationaliteCodeInsee = $paysNationaliteCodeInsee;

        return $this;
    }

    /**
     * Get paysNationaliteCodeInsee
     *
     * @return string 
     */
    public function getPaysNationaliteCodeInsee()
    {
        return $this->paysNationaliteCodeInsee;
    }

    /**
     * Set paysNationaliteLibelle
     *
     * @param string $paysNationaliteLibelle
     * @return Intervenant
     */
    public function setPaysNationaliteLibelle($paysNationaliteLibelle)
    {
        $this->paysNationaliteLibelle = $paysNationaliteLibelle;

        return $this;
    }

    /**
     * Get paysNationaliteLibelle
     *
     * @return string 
     */
    public function getPaysNationaliteLibelle()
    {
        return $this->paysNationaliteLibelle;
    }

    /**
     * Set personnelId
     *
     * @param integer $personnelId
     * @return Intervenant
     */
    public function setPersonnelId($personnelId)
    {
        $this->personnelId = $personnelId;

        return $this;
    }

    /**
     * Get personnelId
     *
     * @return integer 
     */
    public function getPersonnelId()
    {
        return $this->personnelId;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Intervenant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set primeExcellenceScientifique
     *
     * @param string $primeExcellenceScientifique
     * @return Intervenant
     */
    public function setPrimeExcellenceScientifique($primeExcellenceScientifique)
    {
        $this->primeExcellenceScientifique = $primeExcellenceScientifique;

        return $this;
    }

    /**
     * Get primeExcellenceScientifique
     *
     * @return string 
     */
    public function getPrimeExcellenceScientifique()
    {
        return $this->primeExcellenceScientifique;
    }

    /**
     * Set telMobile
     *
     * @param string $telMobile
     * @return Intervenant
     */
    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    /**
     * Get telMobile
     *
     * @return string 
     */
    public function getTelMobile()
    {
        return $this->telMobile;
    }

    /**
     * Set villeNaissanceCodeInsee
     *
     * @param string $villeNaissanceCodeInsee
     * @return Intervenant
     */
    public function setVilleNaissanceCodeInsee($villeNaissanceCodeInsee)
    {
        $this->villeNaissanceCodeInsee = $villeNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get villeNaissanceCodeInsee
     *
     * @return string 
     */
    public function getVilleNaissanceCodeInsee()
    {
        return $this->villeNaissanceCodeInsee;
    }

    /**
     * Set villeNaissanceLibelle
     *
     * @param string $villeNaissanceLibelle
     * @return Intervenant
     */
    public function setVilleNaissanceLibelle($villeNaissanceLibelle)
    {
        $this->villeNaissanceLibelle = $villeNaissanceLibelle;

        return $this;
    }

    /**
     * Get villeNaissanceLibelle
     *
     * @return string 
     */
    public function getVilleNaissanceLibelle()
    {
        return $this->villeNaissanceLibelle;
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
     * Set type
     *
     * @param \Application\Entity\Db\TypeIntervenant $type
     * @return Intervenant
     */
    public function setType(\Application\Entity\Db\TypeIntervenant $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeIntervenant 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Intervenant
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
     * Set civilite
     *
     * @param \Application\Entity\Db\Civilite $civilite
     * @return Intervenant
     */
    public function setCivilite(\Application\Entity\Db\Civilite $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return \Application\Entity\Db\Civilite 
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Add annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Intervenant
     */
    public function addAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee[] = $annee;

        return $this;
    }

    /**
     * Remove annee
     *
     * @param \Application\Entity\Db\Annee $annee
     */
    public function removeAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee->removeElement($annee);
    }

    /**
     * Get annee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Add sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     * @return Intervenant
     */
    public function addSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu)
    {
        $this->sectionCnu[] = $sectionCnu;

        return $this;
    }

    /**
     * Remove sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     */
    public function removeSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu)
    {
        $this->sectionCnu->removeElement($sectionCnu);
    }

    /**
     * Get sectionCnu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSectionCnu()
    {
        return $this->sectionCnu;
    }
}
