<?php

$settings = [
    'view-dirs'            => [getcwd() . '/code'],
    'template-dirs'        => [getcwd() . '/code/template'],
    'generator-dirs'       => [getcwd() . '/code/generator'],
    'generator-output-dir' => '/app/cache/UnicaenCode',
    'author'               => 'Laurent Lécluse <laurent.lecluse at unicaen.fr>',
    'triggers'             => [
        [
            'template'  => 'AwareTrait',
            'type'      => ['Service', 'Hydrator', 'Provider', 'Processus', 'Connecteur', 'Listener'],
            'generator' => function (array $params): array {
                $params['template'] = 'ServiceAwareTrait';

                return $params;
            },
        ],
        [
            'template'    => 'AwareTrait',
            'targetClass' => [\Application\Connecteur\LdapConnecteur::class, \Application\Entity\Db\FormuleTestIntervenant::class],
            'generator'   => function (array $params): array {
                return [];
            },
        ],
        [
            'template'  => 'AwareTrait',
            'type'      => ['Form', 'Fieldset'],
            'generator' => function (array $params): array {
                $params['template'] = 'FormAwareTrait';

                return $params;
            },
        ],
    ],
];


return [
    'unicaen-code' => $settings,
];