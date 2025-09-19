<?php

use Framework\Application\Application;

$config = Application::getInstance()->config();

return [
    'export-rh' => [
        'actif'       => $config['export-rh']['actif'] ?? false,
        'connecteur'  => $config['export-rh']['connecteur'] ?? '',
        'sync-code'   => $config['export-rh']['sync-code'] ?? false,
        'sync-source' => $config['export-rh']['sync-source'] ?? '',
        'sync-code-rh' => $config['export-rh']['sync-code-rh'] ?? '',
    ],
];