<?php

namespace Application;

use Application\Form\Intervenant\AutresForm;
use Application\Provider\Privilege\Privileges;
use Application\Service\DossierAutreService;
use Application\Service\DossierAutreTypeService;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'autres-infos' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/autres',
                    'defaults' => [
                        'controller' => 'Application\Controller\Autres',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:dossierAutre]',
                            'constraints' => [
                                'dossierAutre' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Autres',
                    'action'     => ['index'],
                    'privileges' => Privileges::INTERVENANT_AUTRES_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\Autres',
                    'action'     => ['saisie'],
                    'privileges' => Privileges::INTERVENANT_AUTRES_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Autres' => Controller\AutresController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            DossierAutreService::class     => DossierAutreService::class,
            DossierAutreTypeService::class => DossierAutreTypeService::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            AutresForm::class => AutresForm::class,
        ],
    ],
];
