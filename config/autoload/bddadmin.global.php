<?php

$config = \OseAdmin::instance()->config();

return [
    'unicaen-bddadmin' => [

        'connection' => [
            'default' => [
                'driver'   => 'Oracle',
                'host'     => $config->get('bdd', 'host'),
                'port'     => $config->get('bdd', 'port'),
                'dbname'   => $config->get('bdd', 'dbname'),
                'user'     => $config->get('bdd', 'username'),
                'password' => $config->get('bdd', 'password'),
            ],
            'deploy' => [
                'driver'   => 'Oracle',
                'host'     => 'woracle07.unicaen.fr',
                'port'     => '1524',
                'dbname'   => 'OSEDEV',
                'username' => 'deploy',
                'password' => 'mdp_deploy',
            ],
        ],

        'ddl' => [
            'dir'                    => 'data/ddl',
            'columns_positions_file' => 'data/ddl_columns_pos.php',
        ],

        'data' => [
            'sources' => [],
            'actions' => [],
            'config'  => [], // configuration par tables
        ],

        'migration' => [
            'dir' => 'Application/src/Migration',
        ],

        'id_column' => 'ID',

        'histo' => [
            'histo_creation_column'        => 'HISTO_CREATION',
            'histo_modification_column'    => 'HISTO_MODIFICATION',
            'histo_destruction_column'     => 'HISTO_DESTRUCTION',
            'histo_createur_id_column'     => 'HISTO_CREATEUR_ID',
            'histo_modificateur_id_column' => 'HISTO_MODIFICATEUR_ID',
            'histo_destructeur_id_column'  => 'HISTO_DESTRUCTEUR_ID',
        ],

        'import' => [
            'source_id_column'   => 'SOURCE_ID',
            'source_code_column' => 'SOURCE_CODE',
        ],
    ],
];