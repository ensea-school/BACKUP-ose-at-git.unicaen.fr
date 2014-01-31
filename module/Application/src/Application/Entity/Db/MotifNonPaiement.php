<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * MotifNonPaiement
 *
 * @ORM\Table(name="MOTIF_NON_PAIEMENT")
 * @ORM\Entity
 */
class MotifNonPaiement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MOTIF_NON_PAIEMENT_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_COURT", type="string", length=50, nullable=false)
     */
    private $libelleCourt;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBELLE_LONG", type="string", length=200, nullable=false)
     */
    private $libelleLong;



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
     * Set libelleCourt
     *
     * @param string $libelleCourt
     * @return MotifNonPaiement
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
     * @return MotifNonPaiement
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
