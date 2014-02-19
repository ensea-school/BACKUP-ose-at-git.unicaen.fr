<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodeType
 */
class PeriodeType
{
    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set libelle
     *
     * @param string $libelle
     * @return PeriodeType
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
