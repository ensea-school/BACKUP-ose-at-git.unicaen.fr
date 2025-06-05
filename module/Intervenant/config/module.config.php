<?php

namespace Intervenant;

return [
    'services' => [
        Service\TypeIntervenantService::class => Service\TypeIntervenantServiceFactory::class,
        Service\CiviliteService::class        => Service\CiviliteServiceFactory::class,
    ],
];