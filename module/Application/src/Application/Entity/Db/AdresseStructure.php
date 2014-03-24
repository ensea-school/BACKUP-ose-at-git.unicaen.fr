<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdresseStructure
 */
class AdresseStructure
{
    /**
     * @var string
     */
    private $codePostal;

    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var string
     */
    private $localite;

    /**
     * @var string
     */
    private $nomVoie;

    /**
     * @var string
     */
    private $noVoie;

    /**
     * @var string
     */
    private $paysCodeInsee;

    /**
     * @var string
     */
    private $paysLibelle;

    /**
     * @var boolean
     */
    private $principale;

    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var \DateTime
     */
    private $validiteDebut;

    /**
     * @var \DateTime
     */
    private $validiteFin;

    /**
     * @var string
     */
    private $ville;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;


    /**
     * Set codePostal
     *
     * @param string $codePostal
     * @return AdresseStructure
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return AdresseStructure
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
     * @return AdresseStructure
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
     * @return AdresseStructure
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

    /**
     * Set localite
     *
     * @param string $localite
     * @return AdresseStructure
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return string 
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * Set nomVoie
     *
     * @param string $nomVoie
     * @return AdresseStructure
     */
    public function setNomVoie($nomVoie)
    {
        $this->nomVoie = $nomVoie;

        return $this;
    }

    /**
     * Get nomVoie
     *
     * @return string 
     */
    public function getNomVoie()
    {
        return $this->nomVoie;
    }

    /**
     * Set noVoie
     *
     * @param string $noVoie
     * @return AdresseStructure
     */
    public function setNoVoie($noVoie)
    {
        $this->noVoie = $noVoie;

        return $this;
    }

    /**
     * Get noVoie
     *
     * @return string 
     */
    public function getNoVoie()
    {
        return $this->noVoie;
    }

    /**
     * Set paysCodeInsee
     *
     * @param string $paysCodeInsee
     * @return AdresseStructure
     */
    public function setPaysCodeInsee($paysCodeInsee)
    {
        $this->paysCodeInsee = $paysCodeInsee;

        return $this;
    }

    /**
     * Get paysCodeInsee
     *
     * @return string 
     */
    public function getPaysCodeInsee()
    {
        return $this->paysCodeInsee;
    }

    /**
     * Set paysLibelle
     *
     * @param string $paysLibelle
     * @return AdresseStructure
     */
    public function setPaysLibelle($paysLibelle)
    {
        $this->paysLibelle = $paysLibelle;

        return $this;
    }

    /**
     * Get paysLibelle
     *
     * @return string 
     */
    public function getPaysLibelle()
    {
        return $this->paysLibelle;
    }

    /**
     * Set principale
     *
     * @param boolean $principale
     * @return AdresseStructure
     */
    public function setPrincipale($principale)
    {
        $this->principale = $principale;

        return $this;
    }

    /**
     * Get principale
     *
     * @return boolean 
     */
    public function getPrincipale()
    {
        return $this->principale;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return AdresseStructure
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string 
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return AdresseStructure
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return AdresseStructure
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return AdresseStructure
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return AdresseStructure
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
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
     * @return AdresseStructure
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return AdresseStructure
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return AdresseStructure
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
     * @return AdresseStructure
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
     * @return AdresseStructure
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
}
