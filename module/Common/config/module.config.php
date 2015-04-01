<?php

namespace Application;

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'string_functions' => [
                    'CONVERT'  => 'Common\ORM\Query\Functions\Convert',
                    'CONTAINS' => 'Common\ORM\Query\Functions\Contains',
                    'REPLACE'  => 'Common\ORM\Query\Functions\Replace',
                    'OSE_DIVERS_STRUCTURE_DANS_STRUCTURE' => 'Common\ORM\Query\Functions\OseDivers\StructureDansStructure',
                    'compriseEntre' => 'Common\ORM\Query\Functions\OseDivers\CompriseEntre',
                ],
                'filters' => [
                    'historique' => 'Common\ORM\Filter\HistoriqueFilter',
                ],
            ]
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '/%s/Oracle_Errors.php',
            ],
        ],
    ],
];
