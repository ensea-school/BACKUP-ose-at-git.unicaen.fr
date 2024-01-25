<?php

namespace Intervenant;

return [
    'services' => [
        Service\TypeIntervenantService::class => Service\TypeIntervenantServiceFactory::class,
        Service\MailService::class            => Service\MailServiceFactory::class,
        Service\CiviliteService::class        => Service\CiviliteServiceFactory::class,
    ],
];