<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Diplome
 *
 * @ORM\Table(name="DIPLOME", indexes={@ORM\Index(name="IDX_D409B354C2443469", columns={"TYPE_ID"})})
 * @ORM\Entity
 */
class Diplome
{
    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DIPLOME_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=40, nullable=false)
     */
    private $libelle;

    /**
     * @var \Application\Entity\Db\TypeDiplome
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\TypeDiplome")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_ID", referencedColumnName="ID")
     * })
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Db\Etape", inversedBy="diplome")
     * @ORM\JoinTable(name="diplome_etape",
     *   joinColumns={
     *     @ORM\JoinColumn(name="DIPLOME_ID", referencedColumnName="ID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="ETAPE_ID", referencedColumnName="ID")
     *   }
     * )
     */
    private $etape;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etape = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return Diplome
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
     * Set type
     *
     * @param \Application\Entity\Db\TypeDiplome $type
     * @return Diplome
     */
    public function setType(\Application\Entity\Db\TypeDiplome $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeDiplome 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add etape
     *
     * @param \Application\Entity\Db\Etape $etape
     * @return Diplome
     */
    public function addEtape(\Application\Entity\Db\Etape $etape)
    {
        $this->etape[] = $etape;

        return $this;
    }

    /**
     * Remove etape
     *
     * @param \Application\Entity\Db\Etape $etape
     */
    public function removeEtape(\Application\Entity\Db\Etape $etape)
    {
        $this->etape->removeElement($etape);
    }

    /**
     * Get etape
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEtape()
    {
        return $this->etape;
    }
}
