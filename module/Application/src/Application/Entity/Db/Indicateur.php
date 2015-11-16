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