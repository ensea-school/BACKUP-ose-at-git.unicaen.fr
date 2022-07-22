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
        Hydrator\RechercheHydrator::class                => InvokableFactory::class,
        Processus\EnseignementProcessus::class           => InvokableFactory::class,
        Processus\ValidationEnseignementProcessus::class => InvokableFactory::class,
    ],


    'forms' => [

    ],
];