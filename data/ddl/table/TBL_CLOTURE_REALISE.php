<?php

//@formatter:off

return [
    'name'        => 'TBL_CLOTURE_REALISE',
    'temporary'   => FALSE,
    'logging'     => FALSE,
    'commentaire' => 'Clôture de saisie du service réalisé par les intervenants',
    'sequence'    => 'TBL_CLOTURE_REALISE_ID_SEQ',
    'columns'     => [
        'ANNEE_ID'             => [
            'name'        => 'ANNEE_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'CLOTURE'              => [
            'name'        => 'CLOTURE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'commentaire' => 'Témoin (0 ou 1 : 1 si clôturé)',
        ],
        'ID'                   => [
            'name'        => 'ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'INTERVENANT_ID'       => [
            'name'        => 'INTERVENANT_ID',
            'type'        => 'int',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => NULL,
            'nullable'    => FALSE,
            'default'     => NULL,
            'commentaire' => NULL,
        ],
        'PEUT_CLOTURER_SAISIE' => [
            'name'        => 'PEUT_CLOTURER_SAISIE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'commentaire' => 'Témoin (0 ou 1)',
        ],
        'TO_DELETE'            => [
            'name'        => 'TO_DELETE',
            'type'        => 'bool',
            'bdd-type'    => 'NUMBER',
            'length'      => 0,
            'scale'       => '0',
            'precision'   => 1,
            'nullable'    => FALSE,
            'default'     => '0',
            'commentaire' => NULL,
        ],
    ],
];

//@formatter:on
