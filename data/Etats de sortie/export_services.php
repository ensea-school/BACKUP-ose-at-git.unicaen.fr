<?php

/* Préparation des données */
$transData = [
    'variables'    => [
        'type_volume_horaire' => strtolower($options['type_volume_horaire']) . 's',
        'etat_volume_horaire' => strtolower($options['etat_volume_horaire']) . 's',
        'annee'               => $options['annee'],
        'composante'          => $options['composante'],
        'type_intervenant'    => $options['type_intervenant'],
    ],
    'total'        => [
        'intervenant' => 'Total général',
        'sstatutaire' => '',
        'modif_sdu'   => '',
        'solde'       => '',
        'fi'          => 0,
        'fa'          => 0,
        'fc'          => 0,
        'referentiel' => 0,
        'total'       => 0,
    ],
    'intervenants' => [],
];

foreach ($data as $d) {
    $iid = (int)$d['INTERVENANT_ID'];
    $sid = (int)$d['SERVICE_ID'] + (int)$d['SERVICE_REFERENTIEL_ID'];

    if (!isset($transData['intervenants'][$iid])) {
        $transData['intervenants'][$iid] = [
            'intervenant' => $d['INTERVENANT_NOM'],
            'statut'      => $d['INTERVENANT_STATUT_LIBELLE'],
            'grade'       => $d['INTERVENANT_GRADE_LIBELLE'],
            'sstatutaire' => (float)$d['SERVICE_STATUTAIRE'],
            'modif_sdu'   => (float)$d['SERVICE_DU_MODIFIE'],
            'fi'          => 0,
            'fa'          => 0,
            'fc'          => 0,
            'referentiel' => 0,
            'total'       => 0,
            'solde'       => (float)$d['SOLDE'],
            'services'    => [],
        ];
    }

    if (!isset($transData['intervenants'][$iid]['services'][$sid])) {
        if ($d['TYPE_FORMATION_LIBELLE']) {
            $typeFormation = $d['TYPE_FORMATION_LIBELLE'];
        } else {
            if ($d['FONCTION_REFERENTIEL_LIBELLE']) {
                $typeFormation = "Référentiel";
            } else {
                $typeFormation = "Hors établissment";
            }
        }
        $transData['intervenants'][$iid]['services'][$sid] = [
            'composante'     => $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
            'type_formation' => $typeFormation,
            'formation'      => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : $d['ETABLISSEMENT_LIBELLE'],
            'enseignement'   => $d['ELEMENT_LIBELLE'] ? $d['ELEMENT_LIBELLE'] : $d['FONCTION_REFERENTIEL_LIBELLE'],
            'fi'             => 0,
            'fa'             => 0,
            'fc'             => 0,
            'referentiel'    => 0,
            'total'          => 0,
        ];
    }

    $fi    = (float)$d['SERVICE_FI'] + (float)$d['HEURES_COMPL_FI'];
    $fa    = (float)$d['SERVICE_FA'] + (float)$d['HEURES_COMPL_FA'];
    $fc    = (float)$d['SERVICE_FC'] + (float)$d['HEURES_COMPL_FC'] + (float)$d['HEURES_PRIMES'];
    $ref   = (float)$d['SERVICE_REFERENTIEL'] + (float)$d['HEURES_COMPL_REFERENTIEL'];
    $total = $fi + $fa + $fc + $ref;

    $transData['total']['fi']          += $fi;
    $transData['total']['fa']          += $fa;
    $transData['total']['fc']          += $fc;
    $transData['total']['referentiel'] += $ref;
    $transData['total']['total']       += $total;

    $transData['intervenants'][$iid]['fi']          += $fi;
    $transData['intervenants'][$iid]['fa']          += $fa;
    $transData['intervenants'][$iid]['fc']          += $fc;
    $transData['intervenants'][$iid]['referentiel'] += $ref;
    $transData['intervenants'][$iid]['total']       += $total;

    $transData['intervenants'][$iid]['services'][$sid]['fi']          += $fi;
    $transData['intervenants'][$iid]['services'][$sid]['fa']          += $fa;
    $transData['intervenants'][$iid]['services'][$sid]['fc']          += $fc;
    $transData['intervenants'][$iid]['services'][$sid]['referentiel'] += $ref;
    $transData['intervenants'][$iid]['services'][$sid]['total']       += $total;
}


/* Publication des données */
$publisher = $document->getPublisher();
$publisher->publishBegin();

$ligneTemplate = $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'enseignement');
$totalTemplate = $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'solde');


foreach ($transData['intervenants'] as $di) {
    $firstService = true;
    foreach ($di['services'] as $ds) {
        $oriDs = $ds;
        if ($firstService) {
            $ds['intervenant'] = $di['intervenant'];
            $ds['statut']      = $di['statut'];
            $ds['grade']       = $di['grade'];
            $firstService      = false;
        } else {
            $ds['intervenant'] = '';
            $ds['statut']      = '';
            $ds['grade']       = '';
            if ($ds['composante'] == $lastLine['composante']) {
                $ds['composante'] = null;
                if ($ds['type_formation'] == $lastLine['type_formation']) {
                    $ds['type_formation'] = null;
                    if ($ds['formation'] == $lastLine['formation']) $ds['formation'] = null;
                }
            }
        }
        $lastLine = $oriDs;
        $publisher->publishBefore($ligneTemplate, $ds, $ligneTemplate);
    }
    unset($di['services']);
    $di['intervenant'] = 'Total ' . $di['intervenant'];
    $publisher->publishBefore($totalTemplate, $di, $ligneTemplate);
}
$publisher->publishBefore($totalTemplate, $transData['total'], $ligneTemplate);

$publisher->remove($ligneTemplate);
$publisher->remove($totalTemplate);

// Publication des variables au niveau du document (dernière ligne du tableau, et fin du traitement
$publisher->publishValues($publisher->getBody(), $transData['variables']);
$publisher->publishEnd();