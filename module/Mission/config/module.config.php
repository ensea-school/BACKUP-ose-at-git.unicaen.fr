<?php

namespace Mission;

use UnicaenPrivilege\Assertion\AssertionFactory;


return [
    'services' => [
        Service\MissionService::class     => Service\MissionServiceFactory::class,
    ],
];