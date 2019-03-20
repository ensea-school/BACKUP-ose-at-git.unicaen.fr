<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Util;

/**
 * TauxHoraireHETD
 */
class TauxHoraireHETD implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var float
     */
    private $valeur;

    /**
     * @var integer
     */
    private $id;



    /**
     * Set valeur
     *
     * @param float $valeur
     *
     * @return self
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }



    /**
     * Get valeur
     *
     * @return float
     */
    public function getValeur()
    {
        return $this->valeur;
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
     *
     * @return string
     */
    public function __toString()
    {
        return Util::formattedNumber($this->getValeur());
    }
}
