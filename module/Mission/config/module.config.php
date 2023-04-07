<?php

namespace Mission;

use UnicaenPrivilege\Assertion\AssertionFactory;


return [
    'services' => [
        Assertion\WorkflowAssertion::class => AssertionFactory::class,
        Service\MissionService::class     => Service\MissionServiceFactory::class,
    ],
];