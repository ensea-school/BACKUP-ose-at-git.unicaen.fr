<?php

//@formatter:off

return [
    'name'        => 'NOTIF_INDIC_AFFECTATION_FK',
    'table'       => 'NOTIFICATION_INDICATEUR',
    'rtable'      => 'AFFECTATION',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'AFFECTATION_ID' => 'ID',
    ],
];

//@formatter:on
