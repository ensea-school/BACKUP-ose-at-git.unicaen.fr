<?php

namespace Plafond\Entity;

use Plafond\Interfaces\PlafondDataInterface;
use Service\Entity\Db\TypeVolumeHoraire;

class PlafondQueryParams
{
    public TypeVolumeHoraire $typeVolumeHoraire;
    public bool $useView = false;
    public bool $depassementsUniquement = false;
    public bool $bloquantUniquement = false;
    public bool $bloquantOuDepassementUniquement = false;

    public array $entities = [];



    public function sub(): PlafondQueryParams
    {
        $sub = new self;
        $sub->typeVolumeHoraire = $this->typeVolumeHoraire;
        $sub->useView = $this->useView;
        $sub->depassementsUniquement = $this->depassementsUniquement;
        $sub->bloquantUniquement = $this->bloquantUniquement;

        return $sub;
    }



    public function add(?PlafondDataInterface $plafondData): self
    {
        if (!$plafondData) {
            return $this;
        }

        $class = $plafondData::class;
        $proxy = 'DoctrineORMModule\\Proxy\\__CG__\\';
        if (str_starts_with($class, $proxy)){
            $class = substr($class, strlen($proxy));
        }



        if (!array_key_exists($class, $this->entities)) {
            $this->entities[$class] = [];
        }
        $this->entities[$class][$plafondData->getId()] = $plafondData;

        return $this;
    }
}