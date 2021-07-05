<?php


return [
    'export-rh' => [
        'actif'      => AppConfig::get('export-rh', 'actif') ? AppConfig::get('export-rh', 'actif') : false,
        'connecteur' => AppConfig::get('export-rh', 'connecteur') ? AppConfig::get('export-rh', 'connecteur') : '',
    ],

];