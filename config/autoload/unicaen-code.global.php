<?php

$settings = [
    'view-dirs'            => [getcwd() . '/code'],
    'template-dirs'        => [getcwd() . '/code/template'],
    'generator-output-dir' => '/tmp/UnicaenCode',
    'namespaces'           => [
        'services'  => [
            'Application',
            'Import',
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