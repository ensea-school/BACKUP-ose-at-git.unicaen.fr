<?php

namespace Administration;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'gestion' => [
            'route'         => '/gestion',
            'controller'    => Controller\GestionController::class,
            'action'        => 'index',
            'privileges'    => [
                Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE,
                Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
                Privileges::PILOTAGE_VISUALISATION,
                Privileges::BUDGET_VISUALISATION,
                Privileges::INDICATEUR_VISUALISATION,
                Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
            ],
            'assertion'     => Assertion\GestionAssertion::class,
            'may_terminate' => true,
        ],
    ],

    'navigation' => [
        'gestion' => [
            'label'    => "Gestion",
            'route'    => 'gestion',
            'resource' => Authorize::controllerResource(Controller\GestionController::class, 'index'),
            'order'    => 6,
            'pages'    => [
            ],
        ],
    ],

    'controllers' => [
        Controller\GestionController::class => Controller\GestionControllerFactory::class,
    ],
];