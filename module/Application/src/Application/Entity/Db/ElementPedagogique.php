<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementPedagogique
 *
 * @ORM\Table(name="ELEMENT_PEDAGOGIQUE", indexes={@ORM\Index(name="IDX_CCADDAC0C0569A10", columns={"PERIODE_ID"}), @ORM\Index(name="IDX_CCADDAC0884B0F7B", columns={"STRUCTURE_ID"})})
 * @ORM\Entity
 */
class ElementPedagogique
{
    /**
     * @var float
     *
     * @ORM\Column(name="HEURES", type="float", precision=126, scale=0, nullable=false)
     */
    private $heures;

    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=15, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ELEMENT_PEDAGOGIQUE_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=60, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="UE", type="string", length=60, nullable=true)
     */
    private $ue;

    /**
     * @var integer
     *
     * @ORM\Column(name="index", type="integer", nullable=false)
     */
    private $index;

    /**
     * @var \Application\Entity\Db\Periode
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\Periode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PERIODE_ID", referencedColumnName="ID")
     * })
     */
    private $periode;

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
     * Set heures
     *
     * @param float $heures
     * @return ElementPedagogique
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

    /**
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
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
     * @return ElementPedagogique
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
     * Set ue
     *
     * @param string $ue
     * @return ElementPedagogique
     */
    public function setUe($ue)
    {
        $this->ue = $ue;

        return $this;
    }

    /**
     * Get ue
     *
     * @return string 
     */
    public function getUe()
    {
        return $this->ue;
    }

    /**
     * Set index
     *
     * @param integer $index
     * @return ElementPedagogique
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return integer 
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     * @return ElementPedagogique
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return ElementPedagogique
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
