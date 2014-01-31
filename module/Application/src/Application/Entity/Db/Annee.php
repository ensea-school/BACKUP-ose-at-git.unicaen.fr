<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annee
 *
 * @ORM\Table(name="ANNEE")
 * @ORM\Entity
 */
class Annee
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_DEBUT", type="datetime", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_FIN", type="datetime", nullable=false)
     */
    private $dateFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ANNEE_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=9, nullable=false)
     */
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Db\Intervenant", inversedBy="annee")
     * @ORM\JoinTable(name="prime_excellence_scientifique",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ANNEE_ID", referencedColumnName="ID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="INTERVENANT_ID", referencedColumnName="ID")
     *   }
     * )
     */
    private $intervenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->intervenant = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Annee
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Annee
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
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
     * Set libelle
     *
     * @param string $libelle
     * @return Annee
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Add intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return Annee
     */
    public function addIntervenant(\Application\Entity\Db\Intervenant $intervenant)
    {
        $this->intervenant[] = $intervenant;

        return $this;
    }

    /**
     * Remove intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function removeIntervenant(\Application\Entity\Db\Intervenant $intervenant)
    {
        $this->intervenant->removeElement($intervenant);
    }

    /**
     * Get intervenant
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}
