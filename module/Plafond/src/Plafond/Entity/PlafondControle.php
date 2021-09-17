<?php

namespace Plafond\Entity;

use Plafond\Entity\Db\PlafondEtat;

/**
 * Description of PlafondControle
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PlafondControle
{
    /**
     * @var string
     */
    private $plafondLibelle;

    /**
     * @var PlafondEtat
     */
    private $plafondEtat;

    /**
     * @var bool
     */
    private $bloquant = false;

    /**
     * @var bool
     */
    private $depassement = false;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var float
     */
    private $plafond;

    /**
     * @var float
     */
    private $derogation;



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
     * @return PlafondControle
     */
    public function setPlafondLibelle(string $plafondLibelle): PlafondControle
    {
        $this->plafondLibelle = $plafondLibelle;

        return $this;
    }



    /**
     * @return PlafondEtat
     */
    public function getPlafondEtat(): PlafondEtat
    {
        return $this->plafondEtat;
    }



    /**
     * @param PlafondEtat $plafondEtat
     *
     * @return PlafondControle
     */
    public function setPlafondEtat(PlafondEtat $plafondEtat): PlafondControle
    {
        $this->plafondEtat = $plafondEtat;

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
     * @return PlafondControle
     */
    public function setBloquant(bool $bloquant): PlafondControle
    {
        $this->bloquant = $bloquant;

        return $this;
    }



    /**
     * @return bool
     */
    public function isDepassement(): bool
    {
        return $this->depassement;
    }



    /**
     * @param bool $depassement
     *
     * @return PlafondControle
     */
    public function setDepassement(bool $depassement): PlafondControle
    {
        $this->depassement = $depassement;

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
     * @return PlafondControle
     */
    public function setHeures(float $heures): PlafondControle
    {
        $this->heures = $heures;

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
     * @return PlafondControle
     */
    public function setPlafond(float $plafond): PlafondControle
    {
        $this->plafond = $plafond;

        return $this;
    }



    /**
     * @return float
     */
    public function getDerogation(): float
    {
        return $this->derogation;
    }



    /**
     * @param float $derogation
     *
     * @return PlafondControle
     */
    public function setDerogation(float $derogation): PlafondControle
    {
        $this->derogation = $derogation;

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