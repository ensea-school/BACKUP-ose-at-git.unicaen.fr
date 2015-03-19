<?php

namespace Application;

return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'string_functions' => array(
                    'CONVERT'  => 'Common\ORM\Query\Functions\Convert',
                    'CONTAINS' => 'Common\ORM\Query\Functions\Contains',
                    'REPLACE'  => 'Common\ORM\Query\Functions\Replace',
                    'OSE_DIVERS_STRUCTURE_DANS_STRUCTURE' => 'Common\ORM\Query\Functions\OseDivers\StructureDansStructure',
                    'compriseEntre' => 'Common\ORM\Query\Functions\OseDivers\CompriseEntre',
                ),
                'filters' => array(
                    'historique' => 'Common\ORM\Filter\HistoriqueFilter',
//                    'validite'   => 'Common\ORM\Filter\ValiditeFilter',
                ),
            )
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '/%s/Oracle_Errors.php',
            ),
        ),
    ),
);
