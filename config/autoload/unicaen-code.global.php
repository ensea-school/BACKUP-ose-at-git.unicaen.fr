<?php

if (defined('APPLICATION_PATH')){
    $settings = [
        'view-dirs'     => [APPLICATION_PATH . '/code'],
        'template-dirs' => [APPLICATION_PATH . '/code/template'],
        'generator-output-dir' => '/tmp/UnicaenCode',
        'namespaces'           => [
            'services'  => [
                'Application\Service',
                'Import\Service',
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
}else{
    $settings = [];
}

return [
    'unicaen-code' => $settings,
];