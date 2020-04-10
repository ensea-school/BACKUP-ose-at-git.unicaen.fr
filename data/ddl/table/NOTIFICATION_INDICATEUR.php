<?php

//@formatter:off

return [
    'name'        => 'NOTIFICATION_INDICATEUR',
    'temporary'   => FALSE,
    'logging'     => TRUE,
    'commentaire' => NULL,
    'sequence'    => 'NOTIFICATION_INDICATEUR_ID_SEQ',
    'columns'     => [
        'AFFECTATION_ID'  => [
            'name'        => 'AFFECTATION_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 1,
            'commentaire' => 'Identifiant du personnel',
        ],
        'DATE_ABONNEMENT' => [
            'name'        => 'DATE_ABONNEMENT',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 2,
            'commentaire' => 'Date d\'abonnement à cette notification',
        ],
        'DATE_DERN_NOTIF' => [
            'name'        => 'DATE_DERN_NOTIF',
            'type'        => 'date',
            'bdd-type'    => 'DATE',
            'length'      => 0,
            'scale'       => NULL,
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 3,
            'commentaire' => 'Eventuelle date de dernière notification',
        ],
        'FREQUENCE'       => [
            'name'        => 'FREQUENCE',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => TRUE,
            'default'     => NULL,
            'position'    => 4,
            'commentaire' => 'Fréquence de notification en secondes (60*60*24=jour, 60*60*24*7=semaine, etc.)',
        ],
        'ID'              => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 5,
            'commentaire' => NULL,
        ],
        'INDICATEUR_ID'   => [
            'name'        => 'INDICATEUR_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'position'    => 6,
            'commentaire' => 'Identifiant de l\'indicateur',
        ],
        'IN_HOME'         => [
            'name'        => 'IN_HOME',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'position'    => 7,
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
