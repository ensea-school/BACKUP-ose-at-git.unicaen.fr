<?php

$settings = [
    'view-dirs'            => [getcwd() . '/code'],
    'template-dirs'        => [getcwd() . '/code/template'],
    'generator-output-dir' => '/app/cache/UnicaenCode',
    'namespaces'           => [
        'services'  => [
            'Application',
            'UnicaenImport',
        ],
        'forms'     => [
            'Application\Form',
        ],
        'hydrators' => [
            'Application\Hydrator',
        ],
        'entities'  => [
            'Application\Entity\Db',
        ],
    ],
];


return [
    'unicaen-code' => $settings,
];