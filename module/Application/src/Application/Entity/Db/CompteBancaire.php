<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteBancaire
 */
class CompteBancaire implements HistoriqueAwareInterface
{
    /**
     * @var string
     */
    private $banqueBic;

    /**
     * @var string
     */
    private $banqueId;

    /**
     * @var string
     */
    private $branche;

    /**
     * @var string
     */
    private $cleRib;

    /**
     * @var string
     */
    private $compte;

    /**
     * @var string
     */
    private $emplacement;

    /**
     * @var string
     */
    private $guichet;

    /**
     * @var string
     */
    private $iban2;

    /**
     * @var string
     */
    private $iban3;

    /**
     * @var string
     */
    private $iban4;

    /**
     * @var string
     */
    private $iban5;

    /**
     * @var string
     */
    private $iban6;

    /**
     * @var string
     */
    private $iban7;

    /**
     * @var string
     */
    private $ibanFin;

    /**
     * @var string
     */
    private $ibanIso;

    /**
     * @var string
     */
    private $paysIso;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenantExterieur;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


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
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return CompteBancaire
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
