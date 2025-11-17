<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_VOLUME_HORAIRE_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
