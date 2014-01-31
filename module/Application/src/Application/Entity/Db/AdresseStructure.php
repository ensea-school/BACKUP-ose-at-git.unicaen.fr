<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdresseStructure
 *
 * @ORM\Table(name="ADRESSE_STRUCTURE", indexes={@ORM\Index(name="IDX_D72AAFC854222575", columns={"BIS_TER_ID"}), @ORM\Index(name="IDX_D72AAFC8884B0F7B", columns={"STRUCTURE_ID"})})
 * @ORM\Entity
 */
class AdresseStructure
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODE_POSTAL", type="string", length=15, nullable=true)
     */
    private $codePostal;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ADRESSE_STRUCTURE_ID_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="PAYS_CODE_INSEE", type="string", length=3, nullable=true)
     */
    private $paysCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_LIBELLE", type="string", length=30, nullable=true)
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
     * @ORM\Column(name="TELEPHONE", type="string", length=20, nullable=false)
     */
    private $telephone;

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
     * @var \Application\Entity\Db\Structure
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="STRUCTURE_ID", referencedColumnName="ID")
     * })
     */
    private $structure;



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
     * Set villeCodeInsee
     *
     * @param string $villeCodeInsee
     * @return AdresseStructure
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
     * @return AdresseStructure
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
     * @return AdresseStructure
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
}
