<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_REF_TVH_FK',
    'table'       => 'TBL_PLAFOND_REFERENTIEL',
    'rtable'      => 'TYPE_VOLUME_HORAIRE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'TYPE_VOLUME_HORAIRE_ID' => 'ID',
    ],
];

//@formatter:on
