<?php

namespace Formule\Model\Arrondisseur;

class Valeur
{
    protected Ligne $ligne;

    protected string $name;
    protected float  $value    = 0.0;
    protected int    $diff     = 0;
    protected int    $arrondi  = 0;
    protected ?float $controle = null;



    public function __construct(Ligne $ligne, string $name)
    {
        $this->ligne = $ligne;
        $this->name  = $name;
    }



    public function getLigne(): Ligne
    {
        return $this->ligne;
    }



    public function getName(): string
    {
        return $this->name;
    }



    public function getValue(): float
    {
        return $this->value;
    }



    public function getParente(): ?Valeur
    {
        $sup = $this->ligne->getSup();
        if (!$sup) {
            return null;
        }
        return $sup->getValeur($this->name);
    }



    public function getValueFinale(): float
    {
        return round($this->value + $this->arrondi / 100, 2);
    }



    public function setValue(float $value): void
    {
        $this->value = $value;

        // Le diff = int des 2 premiers chiffres après la virgule de $value*100
        //$vDiff = round($value * 100,2);
        //$this->diff    = (int)round(($vDiff - floor($vDiff)) * 100);

        $dVal = $value * 100;
        $strVal = (string)$dVal;
        $dotPos = strpos($strVal,'.');
        if (false !== $dotPos){
            $intVal = (int)substr($strVal, 0, $dotPos);
            $dVal -= $intVal;
            $this->diff = (int)(round($dVal, 2)*100);
        }else{
            $this->diff = 0;
        }

        $this->arrondi = 0;
    }



    public function getDiff(): int
    {
        return $this->diff;
    }



    public function setDiff(int $diff): void
    {
        $this->diff = $diff;
    }



    public function getArrondi(): int
    {
        return $this->arrondi;
    }



    public function addArrondi(int $arrondi): Valeur
    {
        $this->arrondi += $arrondi;
        $this->diff -= $arrondi * 100;

        return $this;
    }



    public function getControle(): ?float
    {
        return $this->controle;
    }



    public function setControle(?float $controle): Valeur
    {
        $this->controle = $controle;
        return $this;
    }



    public function isControleOk(): bool
    {
        if (!$this->hasControle()) {
            return true;
        }
        return abs($this->controle - $this->getValueFinale()) < 0.00001;
    }



    public function hasControle(): bool
    {
        return null !== $this->controle;
    }

}