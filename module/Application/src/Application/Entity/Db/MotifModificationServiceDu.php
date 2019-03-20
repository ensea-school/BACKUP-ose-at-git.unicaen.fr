<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * MotifModificationService
 */
class MotifModificationServiceDu implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

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
    protected $decharge;

    /**
     * @var float
     */
    protected $multiplicateur;


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
     * Set code
     *
     * @param string $code
     *
     * @return MotifModificationService
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
     * @return MotifModificationService
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
     * @return boolean
     */
    public function getDecharge()
    {
        return $this->decharge;
    }



    /**
     * @param boolean $decharge
     *
     * @return MotifModificationServiceDu
     */
    public function setDecharge($decharge)
    {
        $this->decharge = $decharge;

        return $this;
    }

    /**
     * Set multiplicateur
     *
     * @param int $multiplicateur
     *
     * @return MotifModificationService
     */
    public function setMultiplicateur($multiplicateur)
    {
        $this->multiplicateur = $multiplicateur;

        return $this;
    }



    /**
     * Get mutiplicateur
     *
     * @return int
     */
    public function getMultiplicateur()
    {
        return $this->multiplicateur;
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
}
