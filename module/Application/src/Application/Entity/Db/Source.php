<?php

namespace Application\Entity\Db;

/**
 * Source
 */
class Source
{
    const CODE_SOURCE_HARPEGE = 'Harpege';
    const CODE_SOURCE_OSE     = 'OSE';
    const CODE_SOURCE_TEST    = 'Test';
    const CODE_SOURCE_APOGEE  = 'Apogee';

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

    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }

    public function isOse()
    {
        return $this->code === self::CODE_SOURCE_OSE;
    }
}
