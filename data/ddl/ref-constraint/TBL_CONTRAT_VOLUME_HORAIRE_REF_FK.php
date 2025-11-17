<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_VOLUME_HORAIRE_REF_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'VOLUME_HORAIRE_REF',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_REF_ID' => 'ID',
    ],
];

//@formatter:on
