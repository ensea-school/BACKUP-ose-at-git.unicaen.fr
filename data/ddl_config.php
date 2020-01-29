<?php

return [
    'explicit'                               => true,
    'include-tables-deps'                    => true,
    \BddAdmin\Ddl\DdlTable::class            => ['includes' => [
        'TYPE_DOTATION',
        'ELEMENT_TAUX_REGIMES_SAVE',
        'TBL_DOSSIER2',
    ]],
    \BddAdmin\Ddl\DdlMaterializedView::class => ['includes' => [

    ]],
    \BddAdmin\Ddl\DdlView::class             => ['includes' => [
        'V_FORMULE_LOCAL_I_PARAMS',
        'V_FORMULE_LOCAL_VH_PARAMS',
        'V_TYPE_INTERVENTION_REGLE_EP',
        'V_INTERVENANT_RECHERCHE',
        'V_FORMULE_SERVICE',
        'V_FORMULE_SERVICE_MODIFIE',
        'V_FORMULE_SERVICE_REF',
        'V_FORMULE_VOLUME_HORAIRE_REF',
        'V_WORKFLOW_ETAPE_PERTINENTE',
    ]],
    \BddAdmin\Ddl\DdlPackage::class          => ['includes' => [
        'OSE_IMPORT',
        'UNICAEN_OSE_FORMULE',
    ]],
    \BddAdmin\Ddl\DdlTrigger::class          => ['includes' => [
        'TYPE_INTERVENTION_STRUCTURE_CK',
    ]],
    \BddAdmin\Ddl\DdlSequence::class         => ['includes' => [
        'FORMULE_ID_SEQ',
        'MESSAGE_ID_SEQ',
        'PACKAGE_DEPS_ID_SEQ',
        'PERSONNEL_ID_SEQ',
        'TBL_CHARGENS_SEUILS_ID_SEQ',
        'TBL_CONFIG_CLES_ID_SEQ',
        'TBL_CONFIG_ID_SEQ',
        'TBL_DEPENDANCES_ID_SEQ',
        'TMP_CALCUL_ID_SEQ',
        'TYPE_INTERVENTION_REGLE_ID_SEQ',
        'TYPE_STRUCTURE_ID_SEQ',
        'DROP SEQUENCE ANNEE_ID_SEQ',
        'ETAT_VOLUME_HORAIRE_ID_SEQ',
        'TYPE_VOLUME_HORAIRE_ID_SEQ',
        'ANNEE_ID_SEQ',
    ]],
];