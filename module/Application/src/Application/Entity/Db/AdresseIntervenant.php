<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdresseIntervenant
 */
class AdresseIntervenant
{
    /**
     * @var string
     */
    private $codePostal;

    /**
     * @var string
     */
    private $habitantChez;

    /**
     * @var \DateTime
     */
    private $histoDebut;

    /**
     * @var \DateTime
     */
    private $histoFin;

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
    private $telephoneDomicile;

    /**
     * @var string
     */
    private $villeCodeInsee;

    /**
     * @var string
     */
    private $villeLibelle;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\BisTer
     */
    private $bisTer;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    private $histoCreateur;


    /**
     * Set codePostal
     *
     * @param string $codePostal
     * @return AdresseIntervenant
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
     * Set habitantChez
     *
     * @param string $habitantChez
     * @return AdresseIntervenant
     */
    public function setHabitantChez($habitantChez)
    {
        $this->habitantChez = $habitantChez;

        return $this;
    }

    /**
     * Get habitantChez
     *
     * @return string 
     */
    public function getHabitantChez()
    {
        return $this->habitantChez;
    }

    /**
     * Set histoDebut
     *
     * @param \DateTime $histoDebut
     * @return AdresseIntervenant
     */
    public function setHistoDebut($histoDebut)
    {
        $this->histoDebut = $histoDebut;

        return $this;
    }

    /**
     * Get histoDebut
     *
     * @return \DateTime 
     */
    public function getHistoDebut()
    {
        return $this->histoDebut;
    }

    /**
     * Set histoFin
     *
     * @param \DateTime $histoFin
     * @return AdresseIntervenant
     */
    public function setHistoFin($histoFin)
    {
        $this->histoFin = $histoFin;

        return $this;
    }

    /**
     * Get histoFin
     *
     * @return \DateTime 
     */
    public function getHistoFin()
    {
        return $this->histoFin;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return AdresseIntervenant
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
     * @return AdresseIntervenant
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
     * @return AdresseIntervenant
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
     * @return AdresseIntervenant
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
     * @return AdresseIntervenant
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
     * @return AdresseIntervenant
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
     * @return AdresseIntervenant
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
     * Set telephoneDomicile
     *
     * @param string $telephoneDomicile
     * @return AdresseIntervenant
     */
    public function setTelephoneDomicile($telephoneDomicile)
    {
        $this->telephoneDomicile = $telephoneDomicile;

        return $this;
    }

    /**
     * Get telephoneDomicile
     *
     * @return string 
     */
    public function getTelephoneDomicile()
    {
        return $this->telephoneDomicile;
    }

    /**
     * Set villeCodeInsee
     *
     * @param string $villeCodeInsee
     * @return AdresseIntervenant
     */
    public function setVilleCodeInsee($villeCodeInsee)
    {
        $this->villeCodeInsee = $villeCodeInsee;

        return $this;
    }

    /**
     * Get villeCodeInsee
     *
     * @return string 
     */
    public function getVilleCodeInsee()
    {
        return $this->villeCodeInsee;
    }

    /**
     * Set villeLibelle
     *
     * @param string $villeLibelle
     * @return AdresseIntervenant
     */
    public function setVilleLibelle($villeLibelle)
    {
        $this->villeLibelle = $villeLibelle;

        return $this;
    }

    /**
     * Get villeLibelle
     *
     * @return string 
     */
    public function getVilleLibelle()
    {
        return $this->villeLibelle;
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return AdresseIntervenant
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
     * Set bisTer
     *
     * @param \Application\Entity\Db\BisTer $bisTer
     * @return AdresseIntervenant
     */
    public function setBisTer(\Application\Entity\Db\BisTer $bisTer = null)
    {
        $this->bisTer = $bisTer;

        return $this;
    }

    /**
     * Get bisTer
     *
     * @return \Application\Entity\Db\BisTer 
     */
    public function getBisTer()
    {
        return $this->bisTer;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Personnel $histoModificateur
     * @return AdresseIntervenant
     */
    public function setHistoModificateur(\Application\Entity\Db\Personnel $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Personnel 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Personnel $histoDestructeur
     * @return AdresseIntervenant
     */
    public function setHistoDestructeur(\Application\Entity\Db\Personnel $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Personnel 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Personnel $histoCreateur
     * @return AdresseIntervenant
     */
    public function setHistoCreateur(\Application\Entity\Db\Personnel $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Personnel 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }
}
