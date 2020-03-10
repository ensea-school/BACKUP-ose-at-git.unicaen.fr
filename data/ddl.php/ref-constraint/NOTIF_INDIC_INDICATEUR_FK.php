<?php

//@formatter:off

return [
    'name'        => 'NOTIF_INDIC_INDICATEUR_FK',
    'table'       => 'NOTIFICATION_INDICATEUR',
    'rtable'      => 'INDICATEUR',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'INDICATEUR_ID' => 'ID',
    ],
];

//@formatter:on
