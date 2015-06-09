<?php

namespace Application\Entity\Db;

/**
 * Perimetre
 */
class Perimetre
{
    const COMPOSANTE    = 'composante';
    const ETABLISSEMENT = 'etablissement';
    const DIPLOME       = 'diplome';

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set code
     *
     * @param string $code
     * @return Perimetre
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
     * Set libelle
     *
     * @param string $libelle
     * @return Perimetre
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
     * Set id
     *
     * @param integer $id
     * @return Perimetre
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function isEtablissement()
    {
        return $this->getCode() === self::ETABLISSEMENT;
    }

    public function isComposante()
    {
        return $this->getCode() === self::COMPOSANTE;
    }

    public function isDiplome()
    {
        return $this->getCode() === self::DIPLOME;
    }
}
