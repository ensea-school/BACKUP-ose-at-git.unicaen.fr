<?php
/* Initialisation : mise en place du formatage des variables */
$pourcFormatter = function($value){
    $value = (float)$value * 100;
    return number_format( $value, 2, ',', ' ').'%';
};
$document->addFormatter('hetd_pourc', $pourcFormatter);
$document->addFormatter('st_hetd_pourc', $pourcFormatter);
$document->addFormatter('t_hetd_pourc', $pourcFormatter);

/* Mise en relief des données à partir de la requête originale */
$variables    = [
    'etat'               => 'Indéfini',
    'periode_composante' => 'Indéfinie',
    'annee'              => 'Indéfinie',
    't_hetd'             => 0,
    't_hetd_montant'     => 0,
    't_rem_fc_d714'      => 0,
    't_exercice_aa'      => 0,
    't_exercice_ac'      => 0,
    'intervenants'       => 0,
];
$intervenants = [];
foreach ($data as $d) {
    $iid = (int)$d['INTERVENANT_ID'];

    $d['HETD']         = (float)$d['HETD'];
    $d['HETD_POURC']   = (float)$d['HETD_POURC'];
    $d['HETD_MONTANT'] = (float)$d['HETD_MONTANT'];
    $d['REM_FC_D714']  = (float)$d['REM_FC_D714'];
    $d['EXERCICE_AA']  = (float)$d['EXERCICE_AA'];
    $d['EXERCICE_AC']  = (float)$d['EXERCICE_AC'];

    $etat = $d['ETAT'] == 'a-mettre-en-paiement' ? 'Demandes de mises en paiement' : 'État de paiement';

    if ($variables['etat'] == 'Indéfini') $variables['etat'] = $etat;
    if ($variables['etat'] != $etat) $variables['etat'] = 'Mises en paiement (état & demandes)';

    $pc = $d['COMPOSANTE'];
    if ($d['PERIODE']) $pc .= "\nPaye du mois de " . $d['PERIODE'];
    if ($variables['periode_composante'] == 'Indéfinie') $variables['periode_composante'] = $pc;
    if ($variables['periode_composante'] != $pc) $variables['periode_composante'] = 'Toutes composantes et périodes';

    if ($variables['annee'] == 'Indéfinie') $variables['annee'] = $d['ANNEE'];
    if ($variables['annee'] != $d['ANNEE']) $variables['annee'] = 'Toutes années';

    if (!isset($intervenants[$iid])) {
        $intervenants[$iid] = [
            'lignes'          => [],
            'st_hetd'         => 0,
            'st_hetd_pourc'   => 0,
            'st_hetd_montant' => 0,
            'st_rem_fc_d714'  => 0,
            'st_exercice_aa'  => 0,
            'st_exercice_ac'  => 0,
        ];
    }

    $intervenants[$iid]['lignes'][] = [
        'intervenant_nom'             => $d['INTERVENANT_NOM'],
        'intervenant_numero_insee'    => $d['INTERVENANT_NUMERO_INSEE'],
        'centre_cout_code'            => $d['CENTRE_COUT_CODE'],
        'domaine_fonctionnel_libelle' => $d['DOMAINE_FONCTIONNEL_LIBELLE'],
        'hetd'                        => $d['HETD'],
        'hetd_pourc'                  => $d['HETD_POURC'],
        'hetd_montant'                => $d['HETD_MONTANT'],
        'rem_fc_d714'                 => $d['REM_FC_D714'],
        'exercice_aa'                 => $d['EXERCICE_AA'],
        'exercice_ac'                 => $d['EXERCICE_AC'],
    ];

    $intervenants[$iid]['st_hetd']         += $d['HETD'];
    $intervenants[$iid]['st_hetd_pourc']   += $d['HETD_POURC'];
    $intervenants[$iid]['st_hetd_montant'] += $d['HETD_MONTANT'];
    $intervenants[$iid]['st_rem_fc_d714']  += $d['REM_FC_D714'];
    $intervenants[$iid]['st_exercice_aa']  += $d['EXERCICE_AA'];
    $intervenants[$iid]['st_exercice_ac']  += $d['EXERCICE_AC'];

    $variables['t_hetd']         += $d['HETD'];
    $variables['t_hetd_montant'] += $d['HETD_MONTANT'];
    $variables['t_rem_fc_d714']  += $d['REM_FC_D714'];
    $variables['t_exercice_aa']  += $d['EXERCICE_AA'];
    $variables['t_exercice_ac']  += $d['EXERCICE_AC'];
}
$variables['intervenants'] = count($intervenants);

/* Exploitation des données pour la publication du codument */
$publisher = $document->getPublisher();
$publisher->publishBegin();

// Récupération des sous-documents, à savoir les lignes de tableau servant de modèles
$detailTemplate = $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'hetd');
$totalTemplate  = $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'st_hetd');

// Publication des lignes
foreach ($intervenants as $intervenant) {
    foreach ($intervenant['lignes'] as $detailData) {
        $publisher->publishBefore($detailTemplate, $detailData, $totalTemplate);
    }
    $totalData = $intervenant;
    unset($totalData['lignes']);
    $publisher->publishBefore($totalTemplate, $totalData, $totalTemplate);
}

// Suppression des lignes de modèles dans le docmument
$publisher->remove($detailTemplate);
$publisher->remove($totalTemplate);

// Publication des variables au niveau du document (dernière ligne du tableau, et fin du traitement
$publisher->publishValues($publisher->getBody(), $variables);
$publisher->publishEnd();