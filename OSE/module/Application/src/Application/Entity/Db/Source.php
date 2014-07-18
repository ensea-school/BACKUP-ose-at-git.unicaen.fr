<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 */
class Source
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var boolean
     */
    protected $importable;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var integer
     */
    protected $id;


    /**
     * Set code
     *
     * @param string $code
     * @return Source
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set importable
     *
     * @param boolean $importable
     * @return Source
     */
    public function setImportable($importable)
    {
        $this->importable = $importable;

        return $this;
    }

    /**
     * Get importable
     *
     * @return boolean 
     */
    public function getImportable()
    {
        return $this->importable;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Source
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


	/**************************************************************************************************
	 * 										Début ajout
	 **************************************************************************************************/

    const ID_SOURCE_HARPEGE = 1;
    const ID_SOURCE_OSE     = 2;
    const ID_SOURCE_TEST    = 3;
    const ID_SOURCE_APOGEE  = 4;

    const CODE_SOURCE_HARPEGE = 'Harpege';
    const CODE_SOURCE_OSE     = 'OSE';
    const CODE_SOURCE_TEST    = 'Test';
    const CODE_SOURCE_APOGEE  = 'Apogee';

    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
