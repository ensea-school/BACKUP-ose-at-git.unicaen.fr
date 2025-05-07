<?php

namespace Administration;

return [
    'unicaen-bddadmin' => [

        'data' => [
            'sources' => [
                10 => DataSource\DataSource::class,
                20 => 'data/nomenclatures.php',
                30 => 'data/donnees_par_defaut.php',
            ],
        ],

        'migration' => [
            Migration\v23minimum::class,
            Migration\v24Formules::class,
            Migration\v24MisesEnPaiement::class,
            Migration\v24Primes::class,
            Migration\v24Signature::class,
            Migration\v24ParametresContrat::class,
            Migration\v24FonctionReferentielParent::class,
            Migration\v25CentreCoutsTypeMission::class,
        ],
    ],

    'services' => [
        DataSource\DataSource::class                 => DataSource\DataSourceFactory::class,
    ],

    'controllers' => [
        Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
    ],
];