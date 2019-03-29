<?php
/* Initialisation : mise en place du formatage des variables */
$pourcFormatter = function ($value) {
if ($value == '' || $value < 0.02) return '';
$value = (float)$value * 100;

return number_format($value, 2, ',', ' ') . '%';
};
$curFormatter   = function ($value) {
if ($value == '') return '';
$value = (float)$value;

return number_format($value, 2, ',', ' ') . ' €';
};

$typesLignes = ['', 'st_', 't_'];

$champs = [
'hetd'                => null,
'hetd_pourc'          => $pourcFormatter,
'hetd_montant'        => $curFormatter,
'rem_fc_d714'         => $curFormatter,
'exercice_aa'         => null,
'exercice_ac'         => null,
'exercice_aa_montant' => $curFormatter,
'exercice_ac_montant' => $curFormatter,
];

foreach ($champs as $champ => $formatter) {
if (null !== $formatter) {
foreach ($typesLignes as $typeLigne) {
$document->addFormatter($typeLigne . $champ, $formatter);
}
}
}


/* Mise en relief des données à partir de la requête originale */
$variables = [
'etat'               => 'Indéfini',
'periode_composante' => 'Indéfinie',
'annee'              => 'Indéfinie',
'intervenants'       => 0,
];

foreach ($champs as $champ => $null) {
$variables['t_' . $champ] = 0;
}

$intervenants = [];
foreach ($data as $d) {
$iid = (int)$d['INTERVENANT_ID'];

$etat = $d['ETAT'] == 'a-mettre-en-paiement' ? 'Demandes de mises en paiement' : 'État de paiement';

if ($variables['etat'] == 'Indéfini') $variables['etat'] = $etat;
if ($variables['etat'] != $etat) $variables['etat'] = 'Mises en paiement (état & demandes)';

$pc = $d['COMPOSANTE'];
if ($d['PERIODE']) $pc .= "\nPaye du mois de " . $d['PERIODE'];
if ($variables['periode_composante'] == 'Indéfinie') $variables['periode_composante'] = $pc;
if ($variables['periode_composante'] != $pc) $variables['periode_composante'] = 'Toutes composantes et périodes';

if ($variables['annee'] == 'Indéfinie') $variables['annee'] = $d['ANNEE'];
if ($variables['annee'] != $d['ANNEE']) $variables['annee'] = 'Toutes années';

$k = (float)$d['REM_FC_D714'] != 0 ? 'p' : 'h';

if (!isset($intervenants[$iid])) {
$intervenants[$iid]['h'] = [];
$intervenants[$iid]['p'] = [];
foreach ($champs as $champ => $null) {
$intervenants[$iid]['h']['st_' . $champ] = 0;
$intervenants[$iid]['p']['st_' . $champ] = 0;
}
}

$newLigne = [
'intervenant_nom'             => $d['INTERVENANT_NOM'],
'intervenant_numero_insee'    => $d['INTERVENANT_NUMERO_INSEE'],
'centre_cout_code'            => $d['CENTRE_COUT_CODE'],
'domaine_fonctionnel_libelle' => $d['DOMAINE_FONCTIONNEL_LIBELLE'],
];

foreach ($champs as $champ => $null) {
$newLigne[$champ]                       = (float)$d[strtoupper($champ)];
$intervenants[$iid][$k]['st_' . $champ] += $newLigne[$champ];
$variables['t_' . $champ]               += $newLigne[$champ];
}
$intervenants[$iid][$k]['lignes'][] = $newLigne;
}
$variables['intervenants'] = count($intervenants);

/* Exploitation des données pour la publication du codument */
$publisher = $document->getPublisher();
$publisher->publishBegin();

// Récupération des sous-documents, à savoir les lignes de tableau servant de modèles
$detailTemplate = $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'hetd');
$totalTemplate  = [
'p' => $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'st_rem_fc_d714'),
'h' => $publisher->getSubDoc($publisher->getBody(), $publisher::TABLE_ROW, 'st_hetd'),
];

// Publication des lignes
foreach ($intervenants as $intervenant) {
foreach ($intervenant as $k => $interv) {
if (count($interv['lignes']) > 0) {
foreach ($interv['lignes'] as $detailData) {
$publisher->publishBefore($detailTemplate, $detailData, $totalTemplate['p']);
}
$totalData = $interv;
unset($totalData['lignes']);
$publisher->publishBefore($totalTemplate[$k], $totalData, $totalTemplate['p']);
}
}
}

// Suppression des lignes de modèles dans le docmument
$publisher->remove($detailTemplate);
$publisher->remove($totalTemplate['p']);
$publisher->remove($totalTemplate['h']);

// Publication des variables au niveau du document (dernière ligne du tableau, et fin du traitement
$publisher->publishValues($publisher->getBody(), $variables);
$publisher->publishEnd();

