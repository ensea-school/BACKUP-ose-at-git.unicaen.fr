<?php

namespace Mission;

use UnicaenPrivilege\Assertion\AssertionFactory;


return [
    'services' => [
        Assertion\MissionAssertion::class => AssertionFactory::class,
        Service\MissionService::class     => Service\MissionServiceFactory::class,
    ],
];