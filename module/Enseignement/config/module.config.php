<?php

namespace Enseignement;

use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'routes' => [

    ],

    'navigation' => [

    ],

    'rules' => [

    ],

    'guards' => [

    ],


    'controllers' => [

    ],

    'services' => [
        Processus\EnseignementProcessus::class           => InvokableFactory::class,
        Processus\ValidationEnseignementProcessus::class => InvokableFactory::class,
    ],


    'forms' => [

    ],
];