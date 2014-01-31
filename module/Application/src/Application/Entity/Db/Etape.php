<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etape
 *
 * @ORM\Table(name="ETAPE", indexes={@ORM\Index(name="IDX_DF0BB76D884B0F7B", columns={"STRUCTURE_ID"})})
 * @ORM\Entity
 */
class Etape
{
    /**
     * @var string
     *
     * @ORM\Column(name="ETAPE_ID", type="string", length=10, nullable=false)
     */
    private $etapeId;

    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=3, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ETAPE_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_COURT", type="string", length=25, nullable=false)
     */
    private $libelleCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_LONG", type="string", length=60, nullable=false)
     */
    private $libelleLong;

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
     * @ORM\ManyToMany(targetEntity="Application\Entity\Db\Diplome", mappedBy="etape")
     */
    private $diplome;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->diplome = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set etapeId
     *
     * @param string $etapeId
     * @return Etape
     */
    public function setEtapeId($etapeId)
    {
        $this->etapeId = $etapeId;

        return $this;
    }

    /**
     * Get etapeId
     *
     * @return string 
     */
    public function getEtapeId()
    {
        return $this->etapeId;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     * @return Etape
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }

    /**
     * Get libelleCourt
     *
     * @return string 
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     * @return Etape
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }

    /**
     * Get libelleLong
     *
     * @return string 
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Etape
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
     * Add diplome
     *
     * @param \Application\Entity\Db\Diplome $diplome
     * @return Etape
     */
    public function addDiplome(\Application\Entity\Db\Diplome $diplome)
    {
        $this->diplome[] = $diplome;

        return $this;
    }

    /**
     * Remove diplome
     *
     * @param \Application\Entity\Db\Diplome $diplome
     */
    public function removeDiplome(\Application\Entity\Db\Diplome $diplome)
    {
        $this->diplome->removeElement($diplome);
    }

    /**
     * Get diplome
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDiplome()
    {
        return $this->diplome;
    }
}
