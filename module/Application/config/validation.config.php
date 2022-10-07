<?php

namespace Application;

use Application\Entity\Db\WfEtape;

return [
    'service_manager' => [
        'invokables' => [
            Service\TypeValidationService::class => Service\TypeValidationService::class,
            Service\ValidationService::class     => Service\ValidationService::class,
        ],
    ],
];
