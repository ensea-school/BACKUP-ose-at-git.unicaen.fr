<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\StructureAwareTrait;

class Scenario
{
    use StructureAwareTrait;

    /**
     * @var integer
     */
    protected $id;

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
    protected $definitif;

    /**
     * @var boolean
     */
    protected $reel;



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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Scenario
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function isDefinitif()
    {
        return $this->definitif;
    }



    /**
     * @param bool $definitif
     *
     * @return Scenario
     */
    public function setDefinitif($definitif)
    {
        $this->definitif = $definitif;

        return $this;
    }



    /**
     * @return bool
     */
    public function isReel()
    {
        return $this->reel;
    }



    /**
     * @param bool $reel
     *
     * @return Scenario
     */
    public function setReel($reel)
    {
        $this->reel = $reel;

        return $this;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}
