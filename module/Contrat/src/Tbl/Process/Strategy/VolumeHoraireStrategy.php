<?php

namespace Contrat\Tbl\Process\Strategy;

use Contrat\Tbl\Process\Model\VolumeHoraire;
use Laminas\Hydrator\ObjectPropertyHydrator;
use Laminas\Hydrator\Strategy\StrategyInterface;

class VolumeHoraireStrategy implements StrategyInterface
{
    public function extract($value, ?object $object = null)
    {
        return array_map([$this,'extractVolumeHoraire'], $value);

    }

    public function hydrate($value, ?array $data = null)
    {
        return array_map([$this,'hydrateVolumeHoraire'], $value);
    }

    public function hydrateVolumeHoraire($vh): VolumeHoraire
    {
        $hydrator = new ObjectPropertyHydrator();
        $volumeHoraire = new VolumeHoraire();
        $hydrator->hydrate($vh, $volumeHoraire);

        return $volumeHoraire;
    }

    public function extractVolumeHoraire($vh): array
    {
        $hydrator = new ObjectPropertyHydrator();
        $data = $hydrator->extract($vh);

        return $data;
    }
}
