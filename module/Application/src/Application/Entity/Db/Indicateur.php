<?php

namespace Application\Entity\Db;

use Application\Service\Traits\IndicateurServiceAwareTrait;

/**
 * Indicateur
 */
class Indicateur
{
    use IndicateurServiceAwareTrait;

    const CODE_DONNEES_PERSO_MODIF = 'DonneesPersoModif';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $numero;

    /**
     * @var string
     */
    private $code;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var string
     */
    private $libelleSingulier;

    /**
     * @var string
     */
    private $libellePluriel;

    /**
     * @var string
     */
    private $route;

    /**
     * @var boolean
     */
    private $distinct;

    /**
     * @var boolean
     */
    private $notStructure;



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return "Indicateur N°" . $this->getNumero();
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
     * Set id interne
     *
     * @param string $numero
     *
     * @return Indicateur
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }



    /**
     * Get id interne
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Indicateur
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }



    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }



    /**
     * Set type
     *
     * @param string $type
     *
     * @return Indicateur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Indicateur
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }



    /**
     * @return string
     */
    public function getLibelleSingulier()
    {
        return $this->libelleSingulier;
    }



    /**
     * @param string $libelleSingulier
     *
     * @return Indicateur
     */
    public function setLibelleSingulier($libelleSingulier)
    {
        $this->libelleSingulier = $libelleSingulier;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibellePluriel()
    {
        return $this->libellePluriel;
    }



    /**
     * @param string $libellePluriel
     *
     * @return Indicateur
     */
    public function setLibellePluriel($libellePluriel)
    {
        $this->libellePluriel = $libellePluriel;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        $count = $this->getCount();

        if ($count > 1) {
            return sprintf($this->getLibellePluriel(), $count);
        } else {
            return sprintf($this->getLibelleSingulier(), $count);
        }
    }



    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }



    /**
     * @param string $route
     *
     * @return Indicateur
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getDistinct()
    {
        return $this->distinct;
    }



    /**
     * @param boolean $distinct
     *
     * @return Indicateur
     */
    public function setDistinct($distinct)
    {
        $this->distinct = $distinct;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getNotStructure()
    {
        return $this->notStructure;
    }



    /**
     * @param boolean $notStructure
     *
     * @return Indicateur
     */
    public function setNotStructure($notStructure)
    {
        $this->notStructure = $notStructure;

        return $this;
    }



    /**
     * @return int
     */
    public function getCount()
    {
        return $this->getServiceIndicateur()->getCount($this);
    }



    /**
     * @return Indicateur\AbstractIndicateur[]
     */
    public function getResult()
    {
        return $this->getServiceIndicateur()->getResult($this);
    }
}