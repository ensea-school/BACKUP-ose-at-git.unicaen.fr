<?php

namespace Intervenant;

use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Statut;

return [
    'routes' => [
        'statut' => [
            'route'         => '/statut',
            'controller'    => 'Intervenant\Controller\Statut',
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'route'       => '/saisie[/:statut]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
                'delete' => [
                    'route'       => '/delete/:statut',
                    'action'      => 'delete',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
                'trier'  => [
                    'route'  => '/trier',
                    'action' => 'trier',
                ],
                'clone'  => [
                    'route'       => '/clone/:statut',
                    'action'      => 'clone',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'resources' => [
        'Statut',
    ],

    'rules' => [
        [
            'privileges' => Privileges::INTERVENANT_STATUT_EDITION,
            'resources'  => 'Statut',
            'assertion'  => Assertion\StatutAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => 'Intervenant\Controller\Statut',
            'action'     => ['index', 'saisie'],
            'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
        ],
        [
            'controller' => 'Intervenant\Controller\Statut',
            'action'     => ['delete', 'trier', 'clone'],
            'privileges' => [Privileges::INTERVENANT_STATUT_EDITION],
        ],
    ],

    'controllers' => [
        'Intervenant\Controller\Statut' => Controller\StatutControllerFactory::class,
    ],

    'services' => [
        Service\TypeIntervenantService::class => Service\TypeIntervenantServiceFactory::class,
        Service\StatutService::class          => Service\StatutServiceFactory::class,
        Assertion\StatutAssertion::class      => Assertion\StatutAssertionFactory::class,
    ],


    'forms' => [
        Form\StatutSaisieForm::class => Form\StatutSaisieFormFactory::class,
    ],
];