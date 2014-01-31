<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdresseIntervenant
 *
 * @ORM\Table(name="ADRESSE_INTERVENANT", indexes={@ORM\Index(name="IDX_845CF54B54222575", columns={"BIS_TER_ID"}), @ORM\Index(name="IDX_845CF54B78FF2BCB", columns={"INTERVENANT_ID"})})
 * @ORM\Entity
 */
class AdresseIntervenant
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODE_POSTAL", type="string", length=15, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="HABITANT_CHEZ", type="string", length=32, nullable=true)
     */
    private $habitantChez;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ADRESSE_INTERVENANT_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LOCALITE", type="string", length=26, nullable=true)
     */
    private $localite;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_VOIE", type="string", length=22, nullable=true)
     */
    private $nomVoie;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_VOIE", type="string", length=4, nullable=true)
     */
    private $noVoie;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_CODE_INSEE", type="string", length=3, nullable=false)
     */
    private $paysCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_LIBELLE", type="string", length=30, nullable=false)
     */
    private $paysLibelle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PRINCIPALE", type="boolean", nullable=true)
     */
    private $principale;

    /**
     * @var string
     *
     * @ORM\Column(name="TELEPHONE_DOMICILE", type="string", length=25, nullable=true)
     */
    private $telephoneDomicile;

    /**
     * @var string
     *
     * @ORM\Column(name="VILLE_CODE_INSEE", type="string", length=5, nullable=true)
     */
    private $villeCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="VILLE_LIBELLE", type="string", length=26, nullable=true)
     */
    private $villeLibelle;

    /**
     * @var \Application\Entity\Db\BisTer
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\BisTer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="BIS_TER_ID", referencedColumnName="ID")
     * })
     */
    private $bisTer;

    /**
     * @var \Application\Entity\Db\Intervenant
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Intervenant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="ID")
     * })
     */
    private $intervenant;



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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
}
