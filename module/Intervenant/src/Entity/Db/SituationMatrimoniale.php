<?php

namespace Intervenant\Entity\Db;


/**
 * Civilite
 */
class SituationMatrimoniale
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $code;



    public function __toString()
    {
        return $this->getLibelle();
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return SituationMatrimoniale
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
     * Set code
     *
     * @param string $code
     *
     * @return SituationMatrimoniale
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

}
