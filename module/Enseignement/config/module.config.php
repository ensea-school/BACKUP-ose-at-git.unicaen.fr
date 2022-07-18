<?php

namespace Enseignement;

use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'routes' => [

    ],

    'navigation' => [

    ],

    'resources' => [

    ],

    'rules' => [

    ],

    'guards' => [

    ],


    'controllers' => [

    ],

    'services' => [
        Hydrator\RechercheHydrator::class => InvokableFactory::class,
    ],


    'forms' => [

    ],
];