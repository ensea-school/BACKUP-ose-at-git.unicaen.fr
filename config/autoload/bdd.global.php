<?php

$config = \OseAdmin::instance()->config();

return [
    'unicaen-bddadmin' => [

        'connection' => [
            'default' => [
                'driver'   => $config->get('bdd', 'driver', 'Oracle'),
                'host'     => $config->get('bdd', 'host'),
                'port'     => $config->get('bdd', 'port'),
                'dbname'   => $config->get('bdd', 'dbname'),
                'user'     => $config->get('bdd', 'user', $config->get('bdd', 'username')),
                'password' => $config->get('bdd', 'password'),
            ],
        ],

        'ddl' => [
            'dir'                    => 'data/ddl',
            'columns_positions_file' => 'data/ddl_columns_pos.php',

            'filters' => [],

            'update-bdd-filters' => require 'data/ddl_config.php',

            'update-ddl-filters' => [
                'table'              => ['excludes' => ['UNICAEN_ELEMENT_DISCIPLINE', 'UNICAEN_CORRESP_STRUCTURE_CC', 'SYS_EXPORT_SCHEMA_%', 'ACT_%']],
                'sequence'           => ['excludes' => ['UNICAEN_CORRESP_STRUCTU_ID_SEQ']],
                'primary-constraint' => ['excludes' => ['UNICAEN_CORRESP_STR_CC_PK', 'UNICAEN_ELEMENT_DISCIPLINE_PK', 'ACT_%']],
                'index'              => ['excludes' => ['UNICAEN_CORRESP_STR_CC_PK', 'UNICAEN_ELEMENT_DISCIPLINE_PK', 'ACT_%']],
                'view'               => ['excludes' => ['SRC_%', 'V_DIFF_%', 'V_SYMPA_%', 'V_UNICAEN_OCTOPUS_TITULAIRES', 'V_UNICAEN_OCTOPUS_VACATAIRES']],
                'materialized-view'  => ['includes' => [
                    'MV_EXT_SERVICE',
                    'MV_EXT_DOTATION_LIQUIDATION',
                    'MV_EXT_ETAT_PAIEMENT',
                    'MV_LIEN',
                ]],
                'package'            => ['excludes' => ['UCBN_LDAP', 'UNICAEN_IMPORT_AUTOGEN_PROCS__', 'OSE_ACTUL']],
            ],
        ],

        'data' => [
            'sources' => [
                20 => 'data/nomenclatures.php',
                30 => 'data/donnees_par_defaut.php',
            ],
            'actions' => [
                'privileges' => 'Mise à jour des privilèges dans la base de données',
            ],
            'config'  => require 'data/data_updater_config.php',
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

    'doctrine' => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'driverClass'   => \Doctrine\DBAL\Driver\OCI8\Driver::class,
                    'host'          => $config->get('bdd', 'host'),
                    'port'          => $config->get('bdd', 'port'),
                    'dbname'        => $config->get('bdd', 'dbname'),
                    'user'          => $config->get('bdd', 'user', $config->get('bdd', 'username')),
                    'password'      => $config->get('bdd', 'password'),
                    'charset'       => 'AL32UTF8',
                    'connectstring' => $config->get('bdd', 'connectstring'),
                    //'persistent' => true,
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'metadata_cache'   => 'filesystem',
                //                'query_cache'      => 'filesystem',
                'result_cache'     => 'filesystem',
                'hydration_cache'  => 'array',
                'generate_proxies' => $config->get('bdd', 'generateProxies'),
                'proxy_dir'        => 'cache/DoctrineProxy',
            ],
        ],
        'eventmanager'  => [
            'orm_default' => [
                'subscribers' => [
                    \Doctrine\DBAL\Event\Listeners\OracleSessionInit::class,
                ],
            ],
        ],
        'cache'         => [
            'apc'        => [
                'namespace' => 'OSE__' . __NAMESPACE__,
            ],
            'filesystem' => [
                'class'     => 'Doctrine\Common\Cache\FilesystemCache',
                'directory' => 'cache/Doctrine',
                'namespace' => 'DoctrineModule',
            ],
        ],
    ],
];
