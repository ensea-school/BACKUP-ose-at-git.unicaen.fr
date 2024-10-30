<?php

namespace Administration;

return [
    'unicaen-bddadmin' => [

        'data' => [
            'sources' => [
                10 => DataSource\DataSource::class,
            ],
        ],

        'migration' => [
            Migration\v23minimum::class,
            Migration\v24Formules::class,
            Migration\v24MisesEnPaiement::class,
            Migration\v24Primes::class,
        ],
    ],

    'services' => [
        DataSource\DataSource::class => DataSource\DataSourceFactory::class,
    ],
];