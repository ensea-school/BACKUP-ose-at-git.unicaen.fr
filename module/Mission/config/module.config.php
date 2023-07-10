<?php

namespace Mission;

use Application\Provider\Privilege\Privileges;
use Mission\Controller\PrimeController;
use UnicaenPrivilege\Assertion\AssertionFactory;


return [


    'services' => [
        Service\MissionService::class => Service\MissionServiceFactory::class,
    ],
];