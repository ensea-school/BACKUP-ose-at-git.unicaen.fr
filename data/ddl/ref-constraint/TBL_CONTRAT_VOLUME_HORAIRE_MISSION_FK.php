<?php

//@formatter:off

return [
    'name'        => 'TBL_CONTRAT_VOLUME_HORAIRE_MISSION_FK',
    'table'       => 'TBL_CONTRAT',
    'rtable'      => 'VOLUME_HORAIRE_MISSION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'VOLUME_HORAIRE_MISSION_ID' => 'ID',
    ],
];

//@formatter:on
