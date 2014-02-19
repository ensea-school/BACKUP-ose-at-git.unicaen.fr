<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * BisTer
 */
class BisTer
{
    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $id;


    /**
     * Set libelle
     *
     * @param string $libelle
     * @return BisTer
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
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
}
