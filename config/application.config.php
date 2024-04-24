<?php

$oa = OseAdmin::instance();

$modules = [
    'Laminas\Cache',
    'Laminas\Filter',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\I18n',
    'Laminas\InputFilter',
    'Laminas\Log',
    'Laminas\Mail',
    'Unicaen\Console',
    'Laminas\Mvc\I18n',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Navigation',
    'Laminas\Paginator',
    'Laminas\Router',
    'Laminas\Session',
    'Laminas\Validator',
    'DoctrineModule',
    'DoctrineORMModule',
    'ZfcUser',
    'UnicaenApp',
    'UnicaenAuthentification',
    'UnicaenMail',
    'UnicaenUtilisateur',
    'UnicaenPrivilege',
    'UnicaenImport',
    'UnicaenTbl',
    'UnicaenSiham',
    'UnicaenVue',
    'UnicaenSignature',
    'Application',
    'Agrement',
    'Intervenant',
    'Service',
    'Enseignement',
    'Referentiel',
    'Mission',
    'Paiement',
    'OffreFormation',
    'PieceJointe',
    'Plafond',
    'Indicateur',
    'ExportRh',
    'Dossier',
    'Contrat',
    'Lieu',
    'Parametre',
    'Signature',
    'Formule',
];

if (!$oa->env()->inConsole()) {
    array_unshift($modules, 'BjyAuthorize'); // ne charge BjyAuthorize QUE si on n'est pas en mode console
}

if ($oa->env()->inDev()) {
    $modules[] = 'Laminas\DeveloperTools';
}

if ($oa->env()->inConsole() || $oa->env()->inDev()) {
    $modules[] = 'UnicaenCode';
}

return [
    'translator'              => [
        'locale' => 'fr_FR',
    ],
    'modules'                 => $modules,
    'module_listener_options' => [
        'config_glob_paths'        => [
            'config/autoload/{,*.}{global,local' . ($oa->env()->inDev() ? ',dev' : '') . '}.php',
        ],
        'module_paths'             => [
            './module',
            './vendor',
        ],
        'cache_dir'                => 'cache/',
        'config_cache_enabled'     => ($oa->env()->inProd() && !$oa->env()->inConsole()),
        'module_map_cache_enabled' => ($oa->env()->inProd() && !$oa->env()->inConsole()),
    ],
];