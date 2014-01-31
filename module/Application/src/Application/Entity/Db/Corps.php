<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Corps
 *
 * @ORM\Table(name="CORPS")
 * @ORM\Entity
 */
class Corps
{
    /**
     * @var string
     *
     * @ORM\Column(name="ID", type="string", length=4, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="CORPS_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_COURT", type="string", length=4, nullable=false)
     */
    private $libelleCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_LONG", type="string", length=40, nullable=false)
     */
    private $libelleLong;



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
     * @return Corps
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
     * @return Corps
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
}
