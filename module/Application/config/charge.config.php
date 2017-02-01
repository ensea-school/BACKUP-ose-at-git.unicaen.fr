<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    /* 'router'          => [
         'routes' => [
             'charges' => [
                 'child_routes' => [

                 ],
             ],
         ],
     ],

     'bjyauthorize'    => [
         'guards'             => [
             PrivilegeController::class => [
                 [
                     'controller' => 'Application\Controller\Charge',
                     'action' => ['voir'],
                     'privileges' => [

                     ],
                 ],
             ],
         ],
     ],*/
    'controllers'     => [
        'invokables' => [
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'applicationScenario' => Service\ScenarioService::class,
        ],
        'factories'  => [
            'ApplicationChargeProvider'     => Provider\Charge\ChargeProviderFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'diagramme' => View\Helper\Charge\DiagrammeViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [

        ],
    ],
];
