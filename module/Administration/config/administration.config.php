<?php

namespace Administration;

$rubriques = [
    'droits'          => [
        'icon'  => 'fas fa-lock-open',
        'label' => "Droits d'accès",
        'title' => "Gestion des droits d'accès",
        'color' => '#bbcf55',
    ],
    'configuration'   => [
        'icon'  => 'fas fa-gear',
        'label' => "Configuration",
        'title' => "Paramétrages de base de l'application",
        'color' => '#f5e79e',
    ],
    'nomenclatures'   => [
        'icon'  => 'fas fa-folder-open',
        'label' => "Nomenclatures",
        'title' => "Diverses nomenclatures en usage",
        'color' => '#217dd8',
    ],
    'intervenants'    => [
        'icon'  => 'fas fa-user',
        'label' => "Intervenants",
        'title' => "Paramétrages liés aux intervenants",
        'color' => '#E5272E',
    ],
    'odf'             => [
        'icon'  => 'fas fa-user-graduate',
        'label' => "Offre de formation",
        'title' => "Administration du fonctionnement de l'offre de formation",
        'color' => '#71dfd7',
    ],
    'rh'              => [
        'icon'  => 'fas fa-paperclip',
        'label' => "RH",
        'title' => "Nomenclatures et paramétrages relatifs aux ressources humaines",
        'color' => '#9e9e9e',
    ],
    'finances'        => [
        'icon'  => 'fas fa-chart-line',
        'label' => "Finances",
        'title' => "Nomenclatures et paramétrages liés aux aspects financiers",
        'color' => '#eb4995',
    ],
    'synchronisation' => [
        'icon'  => 'fas fa-database',
        'label' => "Synchronisation",
        'title' => "Liaisons entre OSE et le système d'information",
        'color' => '#9f491f',
        'route' => 'import',
    ],
    'signature'       => [
        'icon'  => 'fas fa-signature',
        'label' => "Signature électronique",
        'title' => "Gestion de la signature électronique",
        'color' => '#9f491f',
        'route' => 'signature',
    ],
];


$config = [
    'routes' => [
        'administration' => [
            'route'         => '/administration',
            'controller'    => Controller\AdministrationController::class,
            'action'        => 'index',
            'privileges'    => ['user'],
            'assertion'     => Assertion\AdministrationAssertion::class,
            'may_terminate' => true,
            'child_routes'  => [
                // remplies automatiquement
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'label' => "Administration",
            'route' => 'administration',
            'order' => 7,
            'pages' => [
                // remplies automatiquement
            ],
        ],
    ],

    'services' => [
        Service\ParametresService::class     => Service\ParametresService::class,
        Service\AdministrationService::class => Service\AdministrationServiceFactory::class,
        Service\GitRepoService::class        => Service\GitRepoServiceFactory::class,
    ],

    'controllers' => [
        Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
    ],
];

$order = 1;
foreach ($rubriques as $route => $rubrique) {
    $hasRoute          = array_key_exists('route', $rubrique);
    $rubrique['order'] = $order++;
    if (!$hasRoute) {
        $rubrique['route'] = 'administration/' . $route;
    }

    $config['navigation']['administration']['pages'][$route] = $rubrique;

    if (!$hasRoute) {
        $config['routes']['administration']['child_routes'][$route] = [
            'route'         => '/' . $route,
            'controller'    => Controller\AdministrationController::class,
            'action'        => 'rubrique',
            'privileges'    => ['user'],
            'assertion'     => Assertion\AdministrationAssertion::class,
            'may_terminate' => true,
        ];
    }
}

return $config;