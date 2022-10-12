<?php


return [
    'export-rh' => [
        'actif'      => AppConfig::get('export-rh', 'actif') ? AppConfig::get('export-rh', 'actif') : false,
        'connecteur' => AppConfig::get('export-rh', 'connecteur') ? AppConfig::get('export-rh', 'connecteur') : '',
        'sync-code'  => AppConfig::get('export-rh', 'sync-code') ? AppConfig::get('export-rh', 'sync-code') : false,
    ],

];