<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use Common\Constants;

/**
 * Civilite
 */
class Civilite
{
    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var string
     */
    protected $sexe;

    /**
     * @var integer
     */
    protected $id;


    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     * @return Civilite
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
     * @return Civilite
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
     * Set sexe
     *
     * @param string $sexe
     * @return Civilite
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe()
    {
        return $this->sexe;
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


	/**************************************************************************************************
	 * 										Début ajout
	 **************************************************************************************************/

    const SEXE_M = Constants::SEXE_M;
    const SEXE_F = Constants::SEXE_F;
}
