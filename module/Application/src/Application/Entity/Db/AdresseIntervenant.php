<?php

namespace Application\Entity\Db;
use Application\Constants;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;


/**
 * AdresseIntervenant
 */
class AdresseIntervenant implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
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
     *
     * @return string
     */
    public function __toString()
    {
        $pays = $this->getPaysLibelle();
        if (strtoupper($pays) == Constants::ADRESSE_PAYS_DEFAULT) $pays = '';

        return implode( "\n", array_filter([
            trim( $this->getNoVoie().' '.$this->getNomVoie() ),
            trim( $this->getBatiment() ),
            trim( $this->getMentionComplementaire() ),
            trim( $this->getLocalite() ),
            trim( $this->getCodePostal().' '.$this->getVille() ),
            trim( $pays )
        ]));
    }
}
