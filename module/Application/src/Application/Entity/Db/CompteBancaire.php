<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteBancaire
 */
class CompteBancaire
{
    /**
     * @var string
     */
    protected $banqueBic;

    /**
     * @var string
     */
    protected $banqueId;

    /**
     * @var string
     */
    protected $branche;

    /**
     * @var string
     */
    protected $cleRib;

    /**
     * @var string
     */
    protected $compte;

    /**
     * @var string
     */
    protected $emplacement;

    /**
     * @var string
     */
    protected $guichet;

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
     * @var string
     */
    protected $iban2;

    /**
     * @var string
     */
    protected $iban3;

    /**
     * @var string
     */
    protected $iban4;

    /**
     * @var string
     */
    protected $iban5;

    /**
     * @var string
     */
    protected $iban6;

    /**
     * @var string
     */
    protected $iban7;

    /**
     * @var string
     */
    protected $ibanFin;

    /**
     * @var string
     */
    protected $ibanIso;

    /**
     * @var string
     */
    protected $paysIso;

    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    protected $intervenantExterieur;

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
     * Set banqueBic
     *
     * @param string $banqueBic
     * @return CompteBancaire
     */
    public function setBanqueBic($banqueBic)
    {
        $this->banqueBic = $banqueBic;

        return $this;
    }

    /**
     * Get banqueBic
     *
     * @return string 
     */
    public function getBanqueBic()
    {
        return $this->banqueBic;
    }

    /**
     * Set banqueId
     *
     * @param string $banqueId
     * @return CompteBancaire
     */
    public function setBanqueId($banqueId)
    {
        $this->banqueId = $banqueId;

        return $this;
    }

    /**
     * Get banqueId
     *
     * @return string 
     */
    public function getBanqueId()
    {
        return $this->banqueId;
    }

    /**
     * Set branche
     *
     * @param string $branche
     * @return CompteBancaire
     */
    public function setBranche($branche)
    {
        $this->branche = $branche;

        return $this;
    }

    /**
     * Get branche
     *
     * @return string 
     */
    public function getBranche()
    {
        return $this->branche;
    }

    /**
     * Set cleRib
     *
     * @param string $cleRib
     * @return CompteBancaire
     */
    public function setCleRib($cleRib)
    {
        $this->cleRib = $cleRib;

        return $this;
    }

    /**
     * Get cleRib
     *
     * @return string 
     */
    public function getCleRib()
    {
        return $this->cleRib;
    }

    /**
     * Set compte
     *
     * @param string $compte
     * @return CompteBancaire
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte
     *
     * @return string 
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set emplacement
     *
     * @param string $emplacement
     * @return CompteBancaire
     */
    public function setEmplacement($emplacement)
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    /**
     * Get emplacement
     *
     * @return string 
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }

    /**
     * Set guichet
     *
     * @param string $guichet
     * @return CompteBancaire
     */
    public function setGuichet($guichet)
    {
        $this->guichet = $guichet;

        return $this;
    }

    /**
     * Get guichet
     *
     * @return string 
     */
    public function getGuichet()
    {
        return $this->guichet;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return CompteBancaire
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
     * @return CompteBancaire
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
     * @return CompteBancaire
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
     * Set iban2
     *
     * @param string $iban2
     * @return CompteBancaire
     */
    public function setIban2($iban2)
    {
        $this->iban2 = $iban2;

        return $this;
    }

    /**
     * Get iban2
     *
     * @return string 
     */
    public function getIban2()
    {
        return $this->iban2;
    }

    /**
     * Set iban3
     *
     * @param string $iban3
     * @return CompteBancaire
     */
    public function setIban3($iban3)
    {
        $this->iban3 = $iban3;

        return $this;
    }

    /**
     * Get iban3
     *
     * @return string 
     */
    public function getIban3()
    {
        return $this->iban3;
    }

    /**
     * Set iban4
     *
     * @param string $iban4
     * @return CompteBancaire
     */
    public function setIban4($iban4)
    {
        $this->iban4 = $iban4;

        return $this;
    }

    /**
     * Get iban4
     *
     * @return string 
     */
    public function getIban4()
    {
        return $this->iban4;
    }

    /**
     * Set iban5
     *
     * @param string $iban5
     * @return CompteBancaire
     */
    public function setIban5($iban5)
    {
        $this->iban5 = $iban5;

        return $this;
    }

    /**
     * Get iban5
     *
     * @return string 
     */
    public function getIban5()
    {
        return $this->iban5;
    }

    /**
     * Set iban6
     *
     * @param string $iban6
     * @return CompteBancaire
     */
    public function setIban6($iban6)
    {
        $this->iban6 = $iban6;

        return $this;
    }

    /**
     * Get iban6
     *
     * @return string 
     */
    public function getIban6()
    {
        return $this->iban6;
    }

    /**
     * Set iban7
     *
     * @param string $iban7
     * @return CompteBancaire
     */
    public function setIban7($iban7)
    {
        $this->iban7 = $iban7;

        return $this;
    }

    /**
     * Get iban7
     *
     * @return string 
     */
    public function getIban7()
    {
        return $this->iban7;
    }

    /**
     * Set ibanFin
     *
     * @param string $ibanFin
     * @return CompteBancaire
     */
    public function setIbanFin($ibanFin)
    {
        $this->ibanFin = $ibanFin;

        return $this;
    }

    /**
     * Get ibanFin
     *
     * @return string 
     */
    public function getIbanFin()
    {
        return $this->ibanFin;
    }

    /**
     * Set ibanIso
     *
     * @param string $ibanIso
     * @return CompteBancaire
     */
    public function setIbanIso($ibanIso)
    {
        $this->ibanIso = $ibanIso;

        return $this;
    }

    /**
     * Get ibanIso
     *
     * @return string 
     */
    public function getIbanIso()
    {
        return $this->ibanIso;
    }

    /**
     * Set paysIso
     *
     * @param string $paysIso
     * @return CompteBancaire
     */
    public function setPaysIso($paysIso)
    {
        $this->paysIso = $paysIso;

        return $this;
    }

    /**
     * Get paysIso
     *
     * @return string 
     */
    public function getPaysIso()
    {
        return $this->paysIso;
    }

    /**
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return CompteBancaire
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
     * @return CompteBancaire
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intervenantExterieur
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenantExterieur
     * @return CompteBancaire
     */
    public function setIntervenantExterieur(\Application\Entity\Db\IntervenantExterieur $intervenantExterieur = null)
    {
        $this->intervenantExterieur = $intervenantExterieur;

        return $this;
    }

    /**
     * Get intervenantExterieur
     *
     * @return \Application\Entity\Db\IntervenantExterieur 
     */
    public function getIntervenantExterieur()
    {
        return $this->intervenantExterieur;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return CompteBancaire
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
     * @return CompteBancaire
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
     * @return CompteBancaire
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
