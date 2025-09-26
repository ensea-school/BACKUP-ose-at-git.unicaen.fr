<?php
/**
 * @return array
 * @var $etatSortie    \EtatSortie\Entity\Db\EtatSortie
 * @var $data          array
 * @var $filtres       array
 * @var $entityManager \Doctrine\ORM\EntityManager
 * @var $role          \Utilisateur\Acl\Role
 * @var $options       array
 *
 * @var $csv           \UnicaenApp\View\Model\CsvModel
 */

// initialisation

$head = [
    'type_etat'              => 'Type État',
    'date_extraction'        => 'Date d\'extraction',
    'annee'                  => 'Année universitaire',
    'code_intervenant'       => 'Code intervenant',
    'code_rh'                => 'Code RH',
    'intervenant_nom_prenom' => 'Intervenant (NOM Prénom)',
    'date_naissance'         => 'Date de naissance',
    'email_perso'            => 'Adresse mail personnelle',
    'composante_service'     => 'Composante / service',
    'type_mission'           => 'Mission (Type) - parmi les 8 missions prédéfinies',
    'libelle'                => 'Mission (Libellé)',
    'date_debut'             => 'Date de début mission',
    'date_fin'               => 'Date fin mission',
    'heures'                 => 'Nb d\'heures de la mission',
    'taux_remu'              => 'Taux de rémunération',
    'taux_remu_majore'       => 'Taux majoré',
    'heures_formation'       => 'Nb d\'heures de formation prévues (si tutorat)',
];

$res = [];
$periodes = [];

$months = [
    '01' => 'janvier',
    '02' => 'février',
    '03' => 'mars',
    '04' => 'avril',
    '05' => 'mai',
    '06' => 'juin',
    '07' => 'juillet',
    '08' => 'août',
    '09' => 'septembre',
    '10' => 'octobre',
    '11' => 'novembre',
    '12' => 'décembre',
];

$dateExtraction = new \DateTime();
foreach ($data as $ddb) {
    $id = (int)$ddb['MISSION_ID'];

    $d = [
        'type_etat'              => $ddb['TYPE_ETAT'],
        'date_extraction'        => $dateExtraction,
        'annee'                  => $ddb['ANNEE'],
        'code_intervenant'       => $ddb['CODE_INTERVENANT'],
        'code_rh'                => $ddb['CODE_RH'],
        'intervenant_nom_prenom' => $ddb['INTERVENANT_NOM_PRENOM'],
        'date_naissance'         => \DateTime::createFromFormat('Y-m-d', substr($ddb['DATE_NAISSANCE'], 0, 10)),
        'email_perso'            => $ddb['EMAIL_PERSO'],
        'composante_service'     => $ddb['COMPOSANTE_SERVICE'],
        'type_mission'           => $ddb['TYPE_MISSION'],
        'libelle'                => $ddb['LIBELLE'],
        'date_debut'             => \DateTime::createFromFormat('Y-m-d', substr($ddb['DATE_DEBUT'], 0, 10)),
        'date_fin'               => \DateTime::createFromFormat('Y-m-d', substr($ddb['DATE_FIN'], 0, 10)),
        'heures'                 => (float)$ddb['HEURES'],
        'taux_remu'              => $ddb['TAUX_REMU'],
        'taux_remu_majore'       => $ddb['TAUX_REMU_MAJORE'],
        'heures_formation'       => $ddb['HEURES_FORMATION'],
        'total'                  => 0,
    ];

    if (!isset($res[$id])){
        $res[$id] = $d;
    }

    $anneeId = (int)$ddb['ANNEE_ID'];
    $mois = $ddb['MOIS_REALISATION'];
    $heuresRealisees = (float)$ddb['HEURES_REALISEES'];
    if ($heuresRealisees > 0) {
        if (!array_key_exists($mois, $res[$id])) {
            $res[$id][$mois] = 0;
        }

        $res[$id][$mois] += $heuresRealisees;
        $res[$id]['total'] += $heuresRealisees;

        if (!array_key_exists($mois, $periodes)) {
            if ($mois) {
                [$a,$m] = explode('-',$mois);
                $moisLib = 'Total heures '.$months[$m] . ' ' . $a;
            }else{
                $mois = 'inconnue';
                $moisLib = 'Période inconnue';
            }
            $periodes[$mois] = $moisLib;
        }
    }
}

ksort($periodes);

foreach($periodes as $periode => $libelle){
    $head[$periode] = $libelle;
}
$head['total'] = 'TOTAL GLOBAL';

$result = [];
foreach( $res as $r ){
    if ($r['total'] > 0) {
        $rlt = [];
        foreach ($head as $h => $null) {
            $rlt[$h] = $r[$h] ?? null;
        }
        $result[] = $rlt;
    }
}


$csv->setHeader($head);
$csv->addLines($result);