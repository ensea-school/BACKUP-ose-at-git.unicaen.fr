<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompteBancaire
 *
 * @ORM\Table(name="COMPTE_BANCAIRE")
 * @ORM\Entity
 */
class CompteBancaire
{
    /**
     * @var string
     *
     * @ORM\Column(name="BANQUE_BIC", type="string", length=4, nullable=true)
     */
    private $banqueBic;

    /**
     * @var string
     *
     * @ORM\Column(name="BANQUE_ID", type="string", length=5, nullable=false)
     */
    private $banqueId;

    /**
     * @var string
     *
     * @ORM\Column(name="BRANCHE", type="string", length=2, nullable=true)
     */
    private $branche;

    /**
     * @var string
     *
     * @ORM\Column(name="CLE_RIB", type="string", length=2, nullable=false)
     */
    private $cleRib;

    /**
     * @var string
     *
     * @ORM\Column(name="COMPTE", type="string", length=11, nullable=false)
     */
    private $compte;

    /**
     * @var string
     *
     * @ORM\Column(name="EMPLACEMENT", type="string", length=2, nullable=true)
     */
    private $emplacement;

    /**
     * @var string
     *
     * @ORM\Column(name="GUICHET", type="string", length=5, nullable=false)
     */
    private $guichet;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_2", type="string", length=4, nullable=true)
     */
    private $iban2;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_3", type="string", length=4, nullable=true)
     */
    private $iban3;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_4", type="string", length=4, nullable=true)
     */
    private $iban4;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_5", type="string", length=4, nullable=true)
     */
    private $iban5;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_6", type="string", length=4, nullable=true)
     */
    private $iban6;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_7", type="string", length=4, nullable=true)
     */
    private $iban7;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_FIN", type="string", length=7, nullable=true)
     */
    private $ibanFin;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN_ISO", type="string", length=4, nullable=true)
     */
    private $ibanIso;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_ISO", type="string", length=2, nullable=true)
     */
    private $paysIso;

    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\IntervenantExterieur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="INTERVENANT_ID")
     * })
     */
    private $intervenant;



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
     * Set intervenant
     *
     * @param \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return CompteBancaire
     */
    public function setIntervenant(\Application\Entity\Db\IntervenantExterieur $intervenant)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\IntervenantExterieur 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}
