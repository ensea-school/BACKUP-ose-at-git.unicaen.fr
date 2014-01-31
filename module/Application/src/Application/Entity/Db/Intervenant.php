<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Intervenant
 *
 * @ORM\Table(name="INTERVENANT", indexes={@ORM\Index(name="IDX_FED386B4C2443469", columns={"TYPE_ID"}), @ORM\Index(name="IDX_FED386B48C579EB7", columns={"CIVILITE_ID"}), @ORM\Index(name="IDX_FED386B4884B0F7B", columns={"STRUCTURE_ID"})})
 * @ORM\Entity
 * @ORM\DiscriminatorColumn(name="type_id", type="string")
 * @ORM\DiscriminatorMap({"E" = "IntervenantExterieur", "P" = "IntervenantPermanent"})
 */
class Intervenant
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_NAISSANCE", type="datetime", nullable=false)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="DEP_NAISSANCE_CODE_INSEE", type="string", length=3, nullable=true)
     */
    private $depNaissanceCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="DEP_NAISSANCE_LIBELLE", type="string", length=30, nullable=true)
     */
    private $depNaissanceLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="EMAIL", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="INTERVENANT_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_PATRONYMIQUE", type="string", length=60, nullable=false)
     */
    private $nomPatronymique;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_USUEL", type="string", length=60, nullable=false)
     */
    private $nomUsuel;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_NAISSANCE_CODE_INSEE", type="string", length=3, nullable=false)
     */
    private $paysNaissanceCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_NAISSANCE_LIBELLE", type="string", length=30, nullable=false)
     */
    private $paysNaissanceLibelle;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_NATIONALITE_CODE_INSEE", type="string", length=3, nullable=false)
     */
    private $paysNationaliteCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="PAYS_NATIONALITE_LIBELLE", type="string", length=30, nullable=false)
     */
    private $paysNationaliteLibelle;

    /**
     * @var integer
     *
     * @ORM\Column(name="PERSONNEL_ID", type="integer", nullable=true)
     */
    private $personnelId;

    /**
     * @var string
     *
     * @ORM\Column(name="PRENOM", type="string", length=60, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="PRIME_EXCELLENCE_SCIENTIFIQUE", type="string", length=1, nullable=true)
     */
    private $primeExcellenceScientifique;

    /**
     * @var string
     *
     * @ORM\Column(name="TEL_MOBILE", type="string", length=20, nullable=true)
     */
    private $telMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="VILLE_NAISSANCE_CODE_INSEE", type="string", length=5, nullable=true)
     */
    private $villeNaissanceCodeInsee;

    /**
     * @var string
     *
     * @ORM\Column(name="VILLE_NAISSANCE_LIBELLE", type="string", length=26, nullable=true)
     */
    private $villeNaissanceLibelle;

    /**
     * @var \Application\Entity\Db\TypeIntervenant
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\TypeIntervenant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_ID", referencedColumnName="ID")
     * })
     */
    private $type;

    /**
     * @var \Application\Entity\Db\Civilite
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Civilite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CIVILITE_ID", referencedColumnName="ID")
     * })
     */
    private $civilite;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Db\SectionCnu", inversedBy="intervenant")
     * @ORM\JoinTable(name="intervenant_section_cnu",
     *   joinColumns={
     *     @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="ID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="SECTION_CNU_ID", referencedColumnName="ID")
     *   }
     * )
     */
    private $sectionCnu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\Db\Annee", mappedBy="intervenant")
     */
    private $annee;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sectionCnu = new \Doctrine\Common\Collections\ArrayCollection();
        $this->annee = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Intervenant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set depNaissanceCodeInsee
     *
     * @param string $depNaissanceCodeInsee
     * @return Intervenant
     */
    public function setDepNaissanceCodeInsee($depNaissanceCodeInsee)
    {
        $this->depNaissanceCodeInsee = $depNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get depNaissanceCodeInsee
     *
     * @return string 
     */
    public function getDepNaissanceCodeInsee()
    {
        return $this->depNaissanceCodeInsee;
    }

    /**
     * Set depNaissanceLibelle
     *
     * @param string $depNaissanceLibelle
     * @return Intervenant
     */
    public function setDepNaissanceLibelle($depNaissanceLibelle)
    {
        $this->depNaissanceLibelle = $depNaissanceLibelle;

        return $this;
    }

    /**
     * Get depNaissanceLibelle
     *
     * @return string 
     */
    public function getDepNaissanceLibelle()
    {
        return $this->depNaissanceLibelle;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Intervenant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set nomPatronymique
     *
     * @param string $nomPatronymique
     * @return Intervenant
     */
    public function setNomPatronymique($nomPatronymique)
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }

    /**
     * Get nomPatronymique
     *
     * @return string 
     */
    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }

    /**
     * Set nomUsuel
     *
     * @param string $nomUsuel
     * @return Intervenant
     */
    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }

    /**
     * Get nomUsuel
     *
     * @return string 
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }

    /**
     * Set paysNaissanceCodeInsee
     *
     * @param string $paysNaissanceCodeInsee
     * @return Intervenant
     */
    public function setPaysNaissanceCodeInsee($paysNaissanceCodeInsee)
    {
        $this->paysNaissanceCodeInsee = $paysNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get paysNaissanceCodeInsee
     *
     * @return string 
     */
    public function getPaysNaissanceCodeInsee()
    {
        return $this->paysNaissanceCodeInsee;
    }

    /**
     * Set paysNaissanceLibelle
     *
     * @param string $paysNaissanceLibelle
     * @return Intervenant
     */
    public function setPaysNaissanceLibelle($paysNaissanceLibelle)
    {
        $this->paysNaissanceLibelle = $paysNaissanceLibelle;

        return $this;
    }

    /**
     * Get paysNaissanceLibelle
     *
     * @return string 
     */
    public function getPaysNaissanceLibelle()
    {
        return $this->paysNaissanceLibelle;
    }

    /**
     * Set paysNationaliteCodeInsee
     *
     * @param string $paysNationaliteCodeInsee
     * @return Intervenant
     */
    public function setPaysNationaliteCodeInsee($paysNationaliteCodeInsee)
    {
        $this->paysNationaliteCodeInsee = $paysNationaliteCodeInsee;

        return $this;
    }

    /**
     * Get paysNationaliteCodeInsee
     *
     * @return string 
     */
    public function getPaysNationaliteCodeInsee()
    {
        return $this->paysNationaliteCodeInsee;
    }

    /**
     * Set paysNationaliteLibelle
     *
     * @param string $paysNationaliteLibelle
     * @return Intervenant
     */
    public function setPaysNationaliteLibelle($paysNationaliteLibelle)
    {
        $this->paysNationaliteLibelle = $paysNationaliteLibelle;

        return $this;
    }

    /**
     * Get paysNationaliteLibelle
     *
     * @return string 
     */
    public function getPaysNationaliteLibelle()
    {
        return $this->paysNationaliteLibelle;
    }

    /**
     * Set personnelId
     *
     * @param integer $personnelId
     * @return Intervenant
     */
    public function setPersonnelId($personnelId)
    {
        $this->personnelId = $personnelId;

        return $this;
    }

    /**
     * Get personnelId
     *
     * @return integer 
     */
    public function getPersonnelId()
    {
        return $this->personnelId;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Intervenant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set primeExcellenceScientifique
     *
     * @param string $primeExcellenceScientifique
     * @return Intervenant
     */
    public function setPrimeExcellenceScientifique($primeExcellenceScientifique)
    {
        $this->primeExcellenceScientifique = $primeExcellenceScientifique;

        return $this;
    }

    /**
     * Get primeExcellenceScientifique
     *
     * @return string 
     */
    public function getPrimeExcellenceScientifique()
    {
        return $this->primeExcellenceScientifique;
    }

    /**
     * Set telMobile
     *
     * @param string $telMobile
     * @return Intervenant
     */
    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    /**
     * Get telMobile
     *
     * @return string 
     */
    public function getTelMobile()
    {
        return $this->telMobile;
    }

    /**
     * Set villeNaissanceCodeInsee
     *
     * @param string $villeNaissanceCodeInsee
     * @return Intervenant
     */
    public function setVilleNaissanceCodeInsee($villeNaissanceCodeInsee)
    {
        $this->villeNaissanceCodeInsee = $villeNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get villeNaissanceCodeInsee
     *
     * @return string 
     */
    public function getVilleNaissanceCodeInsee()
    {
        return $this->villeNaissanceCodeInsee;
    }

    /**
     * Set villeNaissanceLibelle
     *
     * @param string $villeNaissanceLibelle
     * @return Intervenant
     */
    public function setVilleNaissanceLibelle($villeNaissanceLibelle)
    {
        $this->villeNaissanceLibelle = $villeNaissanceLibelle;

        return $this;
    }

    /**
     * Get villeNaissanceLibelle
     *
     * @return string 
     */
    public function getVilleNaissanceLibelle()
    {
        return $this->villeNaissanceLibelle;
    }

    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypeIntervenant $type
     * @return Intervenant
     */
    public function setType(\Application\Entity\Db\TypeIntervenant $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeIntervenant 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set civilite
     *
     * @param \Application\Entity\Db\Civilite $civilite
     * @return Intervenant
     */
    public function setCivilite(\Application\Entity\Db\Civilite $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return \Application\Entity\Db\Civilite 
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Intervenant
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
     * Add sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     * @return Intervenant
     */
    public function addSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu)
    {
        $this->sectionCnu[] = $sectionCnu;

        return $this;
    }

    /**
     * Remove sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     */
    public function removeSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu)
    {
        $this->sectionCnu->removeElement($sectionCnu);
    }

    /**
     * Get sectionCnu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSectionCnu()
    {
        return $this->sectionCnu;
    }

    /**
     * Add annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return Intervenant
     */
    public function addAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee[] = $annee;

        return $this;
    }

    /**
     * Remove annee
     *
     * @param \Application\Entity\Db\Annee $annee
     */
    public function removeAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee->removeElement($annee);
    }

    /**
     * Get annee
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
