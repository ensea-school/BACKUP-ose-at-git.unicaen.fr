<?php

namespace Application\Entity\Db;

/**
 * TypeStructure
 */
class TypeStructure implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var boolean
     */
    protected $enseignement;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeStructure
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
     *
     * @return TypeStructure
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
     * Set enseignement
     *
     * @param boolean $enseignement
     *
     * @return TypeStructure
     */
    public function setEnseignement($enseignement)
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    /**
     * Get enseignement
     *
     * @return boolean
     */
    public function getEnseignement()
    {
        return $this->enseignement;
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
