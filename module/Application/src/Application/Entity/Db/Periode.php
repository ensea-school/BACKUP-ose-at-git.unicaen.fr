<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periode
 *
 * @ORM\Table(name="PERIODE", indexes={@ORM\Index(name="IDX_AC86D3E9C2443469", columns={"TYPE_ID"})})
 * @ORM\Entity
 */
class Periode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PERIODE_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE", type="string", length=10, nullable=false)
     */
    private $libelle;

    /**
     * @var \Application\Entity\Db\PeriodeType
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Db\PeriodeType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_ID", referencedColumnName="ID")
     * })
     */
    private $type;



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
     * @return Periode
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
     * @param \Application\Entity\Db\PeriodeType $type
     * @return Periode
     */
    public function setType(\Application\Entity\Db\PeriodeType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\PeriodeType 
     */
    public function getType()
    {
        return $this->type;
    }
}
