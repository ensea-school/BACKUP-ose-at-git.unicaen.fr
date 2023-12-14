<?php

$config = OseAdmin::instance()->config();

return [
    'export-rh' => [
        'actif'       => $config->get('export-rh', 'actif') ? $config->get('export-rh', 'actif') : false,
        'connecteur'  => $config->get('export-rh', 'connecteur') ? $config->get('export-rh', 'connecteur') : '',
        'sync-code'   => $config->get('export-rh', 'sync-code') ? $config->get('export-rh', 'sync-code') : false,
        'sync-source' => $config->get('export-rh', 'sync-source') ? $config->get('export-rh', 'sync-source') : '',
    ],

];