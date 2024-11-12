<?php

return [
    'export-rh' => [
        'actif'       => AppAdmin::config()['export-rh']['actif'] ?? false,
        'connecteur'  => AppAdmin::config()['export-rh']['connecteur'] ?? '',
        'sync-code'   => AppAdmin::config()['export-rh']['sync-code'] ?? false,
        'sync-source' => AppAdmin::config()['export-rh']['sync-source'] ?? '',
    ],
];