<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Structure
 *
 * @ORM\Table(name="STRUCTURE", indexes={@ORM\Index(name="IDX_2BC32905F1A6E54F", columns={"PARENTE_ID"})})
 * @ORM\Entity
 */
class Structure
{
    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="STRUCTURE_ID_seq", allocationSize=1, initialValue=1)
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
     *   @ORM\JoinColumn(name="PARENTE_ID", referencedColumnName="ID")
     * })
     */
    private $parente;



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
     * @return Structure
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
     * @return Structure
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
     * Set parente
     *
     * @param \Application\Entity\Db\Structure $parente
     * @return Structure
     */
    public function setParente(\Application\Entity\Db\Structure $parente = null)
    {
        $this->parente = $parente;

        return $this;
    }

    /**
     * Get parente
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getParente()
    {
        return $this->parente;
    }
}
