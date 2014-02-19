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
    private $batiment;

    /**
     * @var string
     */
    private $codePostal;

    /**
     * @var string
     */
    private $localite;

    /**
     * @var string
     */
    private $mentionComplementaire;

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
    private $telDomicile;

    /**
     * @var string
     */
    private $ville;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set batiment
     *
     * @param string $batiment
     * @return AdresseIntervenant
     */
    public function setBatiment($batiment)
    {
        $this->batiment = $batiment;

        return $this;
    }

    /**
     * Get batiment
     *
     * @return string 
     */
    public function getBatiment()
    {
        return $this->batiment;
    }

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
     * Set mentionComplementaire
     *
     * @param string $mentionComplementaire
     * @return AdresseIntervenant
     */
    public function setMentionComplementaire($mentionComplementaire)
    {
        $this->mentionComplementaire = $mentionComplementaire;

        return $this;
    }

    /**
     * Get mentionComplementaire
     *
     * @return string 
     */
    public function getMentionComplementaire()
    {
        return $this->mentionComplementaire;
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
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return AdresseIntervenant
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
     * Set telDomicile
     *
     * @param string $telDomicile
     * @return AdresseIntervenant
     */
    public function setTelDomicile($telDomicile)
    {
        $this->telDomicile = $telDomicile;

        return $this;
    }

    /**
     * Get telDomicile
     *
     * @return string 
     */
    public function getTelDomicile()
    {
        return $this->telDomicile;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return AdresseIntervenant
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return AdresseIntervenant
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
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return AdresseIntervenant
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
