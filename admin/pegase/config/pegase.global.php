<?php

$config = [
    'Readers'       => [
        \Reader\StructureReader::class,
        \Reader\TypeFormationReader::class,
        \Reader\EtapeReader::class,
        \Reader\ObjetFormationReader::class,
        \Reader\VolumeHoraireReader::class
    ],
    'Adapters'      => [
        \Adapter\StructureAdapter::class,
        \Adapter\TypeFormationAdapter::class,
        \Adapter\EtapeAdapter::class,
        \Adapter\ObjetFormationAdapter::class,
        \Adapter\VolumeHoraireAdapter::class

    ],
    'OdfExtractors' => [
        \OdfExtractor\StructuresOdfExtractor::class,
        \OdfExtractor\TypeFormationOdfExtractor::class,
        \OdfExtractor\EtapesOdfExtractor::class,
        \OdfExtractor\ElementPedagogiqueOdfExtractor::class,
        \OdfExtractor\CheminPedagogiqueOdfExtractor::class,
        \OdfExtractor\VolumeHoraireOdfExtractor::class

    ],
];

return $config;