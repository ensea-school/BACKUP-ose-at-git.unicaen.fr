<?php

return [
    'explicit'          => true,
    'table'             => ['includes' => [
        'ADRESSE_INTERVENANT',
        'ADRESSE_STRUCTURE',
        //'DOSSIER',

    ]],
    'materialized-view' => ['includes' => [

    ]],
    'view'              => ['includes' => [

    ]],
    'package'           => ['includes' => [

    ]],
    'trigger'           => ['includes' => [
        'F_CONTRAT', 'F_CONTRAT_S',
    ]],
    'sequence'          => ['includes' => [

    ]],
    'materialized-view' => ['includes' => [

    ]],
];