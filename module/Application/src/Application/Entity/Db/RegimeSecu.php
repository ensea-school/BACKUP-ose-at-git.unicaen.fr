<?php

namespace Application\Entity\Db;

/**
 * RegimeSecu
 */
class RegimeSecu implements HistoriqueAwareInterface
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
     * @var float
     */
    protected $tauxTaxe;

    /**
     * @var integer
     */
    protected $id;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return RegimeSecu
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
     * @return RegimeSecu
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
     * Set tauxTaxe
     *
     * @param float $tauxTaxe
     *
     * @return RegimeSecu
     */
    public function setTauxTaxe($tauxTaxe)
    {
        $this->tauxTaxe = $tauxTaxe;

        return $this;
    }



    /**
     * Get tauxTaxe
     *
     * @return float
     */
    public function getTauxTaxe()
    {
        return $this->tauxTaxe;
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
}
