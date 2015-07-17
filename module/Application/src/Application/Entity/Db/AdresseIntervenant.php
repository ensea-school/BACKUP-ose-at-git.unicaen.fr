<?php

namespace Application\Entity\Db;

/**
 * AdresseIntervenant
 */
class AdresseIntervenant implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $batiment;

    /**
     * @var string
     */
    protected $codePostal;

    /**
     * @var string
     */
    protected $localite;

    /**
     * @var string
     */
    protected $mentionComplementaire;

    /**
     * @var string
     */
    protected $nomVoie;

    /**
     * @var string
     */
    protected $noVoie;

    /**
     * @var string
     */
    protected $paysCodeInsee;

    /**
     * @var string
     */
    protected $paysLibelle;

    /**
     * @var boolean
     */
    protected $principale;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var string
     */
    protected $telDomicile;

    /**
     * @var string
     */
    protected $ville;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;



    /**
     * Set batiment
     *
     * @param string $batiment
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
     * @return string
     */
    public function __toString()
    {
        $part1   = [];
        $part1[] = $this->getNoVoie();
        $part1[] = $this->getNomVoie();
        $part1[] = $this->getBatiment();
        $part1[] = $this->getMentionComplementaire();
        $part1   = implode(', ', array_filter($part1));

        $part2   = [];
        $part2[] = $this->getLocalite();
        $part2[] = $this->getCodePostal();
        $part2[] = $this->getVille();
        $part2[] = $this->getPaysLibelle();
        $part2   = implode(', ', array_filter($part2));

        $parts   = [];
        $parts[] = $part1;
        $parts[] = $part2;

        return implode(PHP_EOL, array_filter($parts));
    }
}
