<?php

$tables = [
    // anciennes
    'TYPE_DOTATION',

    // à mettre à jour explicitement
    'INTERVENANT', 'DOSSIER', 'STRUCTURE', 'TBL_SERVICE', 'VALIDATION', 'UTILISATEUR','STATUT_INTERVENANT','STRUCTURE'

];
$views  = [
    // anciennes
    'V_FORMULE_LOCAL_I_PARAMS', 'V_FORMULE_LOCAL_VH_PARAMS',
];

return [
    'explicit'                                => true,
    \BddAdmin\Ddl\DdlIndex::class             => ['includes' => ['null']],
    \BddAdmin\Ddl\DdlMaterializedView::class  => ['includes' => ['null']],
    \BddAdmin\Ddl\DdlPackage::class           => ['includes' => []],
    \BddAdmin\Ddl\DdlPrimaryConstraint::class => ['includes' => ['null']],
    \BddAdmin\Ddl\DdlRefConstraint::class     => ['includes' => ['null']],
    \BddAdmin\Ddl\DdlSequence::class          => ['includes' => []],
    \BddAdmin\Ddl\DdlTable::class             => ['includes' => $tables],
    \BddAdmin\Ddl\DdlTrigger::class           => ['includes' => []],
    \BddAdmin\Ddl\DdlUniqueConstraint::class  => ['includes' => ['null']],
    \BddAdmin\Ddl\DdlView::class              => ['includes' => $views],
];