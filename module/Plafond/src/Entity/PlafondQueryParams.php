<?php

namespace Plafond\Entity;

use Plafond\Interfaces\PlafondDataInterface;
use Service\Entity\Db\TypeVolumeHoraire;

class PlafondQueryParams
{
    public TypeVolumeHoraire $typeVolumeHoraire;
    public PlafondDataInterface $entity;
    public bool $useView = false;
    public bool $depassementsUniquement = false;
    public bool $bloquantUniquement = false;



    public function sub(PlafondDataInterface $entity): PlafondQueryParams
    {
        $sub = clone $this;
        $sub->entity = $entity;

        return $sub;
    }
}