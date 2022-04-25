<?php

//@formatter:off

return [
    'name'        => 'TBL_PLAFOND_VH_EP_FK',
    'table'       => 'TBL_PLAFOND_VOLUME_HORAIRE',
    'rtable'      => 'ELEMENT_PEDAGOGIQUE',
    'delete_rule' => 'CASCADE',
    'index'       => NULL,
    'columns'     => [
        'ELEMENT_PEDAGOGIQUE_ID' => 'ID',
    ],
];

//@formatter:on
