<?php

return [
    'PAYS'                    => [],
    'DEPARTEMENT'             => [],
    'VOIRIE'                  => [],
    'ETABLISSEMENT'           => [],
    'STRUCTURE'               => [],
    'DISCIPLINE'              => [],
    'AFFECTATION'             => [],
    'EMPLOYEUR'               => [],
    'CORPS'                   => [],
    'GRADE'                   => [],
    'INTERVENANT'             => [
        'SYNC_HOOK_BEFORE'     => "UNICAEN_IMPORT.REFRESH_MV('MV_INTERVENANT');",
        'SYNC_NON_IMPORTABLES' => true,
        'SYNC_FILTRE'          => "WHERE (import_action <> 'delete' OR (
      (NOT exists(SELECT intervenant_id FROM intervenant_dossier WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
  AND (NOT exists(SELECT intervenant_id FROM piece_jointe WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
  AND (NOT exists(SELECT intervenant_id FROM service WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
))",
    ],
    'AFFECTATION_RECHERCHE'   => [
        'SYNC_FILTRE' => "WHERE INTERVENANT_ID IS NOT NULL",
    ],
    'DOMAINE_FONCTIONNEL'     => [],
    'CENTRE_COUT'             => [],
    'CENTRE_COUT_EP'          => [],
    'CENTRE_COUT_STRUCTURE'   => [],
    'GROUPE_TYPE_FORMATION'   => [],
    'TYPE_FORMATION'          => [],
    'ETAPE'                   => [],
    'ELEMENT_PEDAGOGIQUE'     => [],
    'CHEMIN_PEDAGOGIQUE'      => [],
    'VOLUME_HORAIRE_ENS'      => [],
    'EFFECTIFS'               => [],
    'EFFECTIFS_ETAPE'         => [],
    'ELEMENT_TAUX_REGIMES'    => [],
    'NOEUD'                   => [
        'SYNC_HOOK_AFTER' => "UNICAEN_TBL.CALCULER('chargens');",
    ],
    'LIEN'                    => [],
    'SCENARIO_NOEUD'          => [],
    'SCENARIO_NOEUD_EFFECTIF' => [],
    'SCENARIO_LIEN'           => [],
    'SERVICE'                 => [],
    'SERVICE_REFERENTIEL'     => [],
    'VOLUME_HORAIRE'          => [],
    'VOLUME_HORAIRE_REF'      => [],
    'TYPE_INTERVENTION_EP'    => [],
    'TYPE_MODULATEUR_EP'      => [],
    //'VOLUME_HORAIRE_CHARGE' => [],
];