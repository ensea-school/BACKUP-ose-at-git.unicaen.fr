<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleVolumeHoraire;

class FormuleResultatVolumeHoraire extends FormuleVolumeHoraire
{
    protected float $total = 0.0;



    public function getTotal(): float
    {
        return $this->total;
    }



    public function isStructureExterieur(): bool
    {
        return $this->service !== null && $this->getStructureCode() === null;
    }

}
