<?php

namespace Application\Entity;

/**
 * Description of PlafondDepassement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PlafondDepassement
{
    /**
     * @var string
     */
    private $plafondLibelle;

    /**
     * @var bool
     */
    private $bloquant = false;

    /**
     * @var float
     */
    private $plafond;

    /**
     * @var float
     */
    private $heures;



    /**
     * @return string
     */
    public function getPlafondLibelle(): string
    {
        return $this->plafondLibelle;
    }



    /**
     * @param string $plafondLibelle
     *
     * @return PlafondDepassement
     */
    public function setPlafondLibelle(string $plafondLibelle): PlafondDepassement
    {
        $this->plafondLibelle = $plafondLibelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function isBloquant(): bool
    {
        return $this->bloquant;
    }



    /**
     * @param bool $bloquant
     *
     * @return PlafondDepassement
     */
    public function setBloquant(bool $bloquant): PlafondDepassement
    {
        $this->bloquant = $bloquant;

        return $this;
    }



    /**
     * @return float
     */
    public function getPlafond(): float
    {
        return $this->plafond;
    }



    /**
     * @param float $plafond
     *
     * @return PlafondDepassement
     */
    public function setPlafond(float $plafond): PlafondDepassement
    {
        $this->plafond = $plafond;

        return $this;
    }



    /**
     * @return float
     */
    public function getHeures(): float
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return PlafondDepassement
     */
    public function setHeures(float $heures): PlafondDepassement
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        $errStr = 'Le plafond "%s" a été dépassé. Il est en effet de %s heures pour %s heures saisies.';

        return sprintf(
            $errStr,
            $this->getPlafondLibelle(),
            floatToString($this->getPlafond()),
            floatToString($this->getHeures())
        );
    }

}