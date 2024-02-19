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

}
