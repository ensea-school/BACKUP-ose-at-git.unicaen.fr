<?php

namespace Plafond\Traits;

use Administration\Traits\ParametreEntityTrait;
use Plafond\Entity\Db\PlafondAwareTrait;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Interfaces\PlafondConfigInterface;


trait PlafondConfigTrait
{
    use ParametreEntityTrait;
    use PlafondAwareTrait;

    protected float        $heures      = 0;

    protected ?PlafondEtat $etatPrevu   = null;

    protected ?PlafondEtat $etatRealise = null;



    public function getHeures(): float
    {
        return $this->heures;
    }



    public function setHeures(float $heures): PlafondConfigInterface
    {
        $this->heures = $heures;

        return $this;
    }



    public function getEtatPrevu(): ?PlafondEtat
    {
        return $this->etatPrevu;
    }



    public function setEtatPrevu(?PlafondEtat $etat): PlafondConfigInterface
    {
        $this->etatPrevu = $etat;

        return $this;
    }



    public function getEtatRealise(): ?PlafondEtat
    {
        return $this->etatRealise;
    }



    public function setEtatRealise(?PlafondEtat $etat): PlafondConfigInterface
    {
        $this->etatRealise = $etat;

        return $this;
    }
}