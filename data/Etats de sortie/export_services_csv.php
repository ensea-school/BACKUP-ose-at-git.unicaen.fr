<?php
/**
 * @return array
 * @var $etatSortie    \EtatSortie\Entity\Db\EtatSortie
 * @var $data          array
 * @var $filtres       array
 * @var $entityManager \Doctrine\ORM\EntityManager
 * @var $role          \Application\Acl\Role
 * @var $options       array
 *
 * @var $csv           \UnicaenApp\View\Model\CsvModel
 */

// initialisation

use OffreFormation\Entity\Db\TypeIntervention;

$res               = [];
$shown             = [];
$typesIntervention = [];
$invertTi          = [];
$numericColunms    = [
    'service-statutaire',
    'service-du-modifie',
    'heures-non-payees',
    'heures-ref',
    'service-fi',
    'service-fa',
    'service-fc',
    'service-referentiel',
    'heures-compl-fi',
    'heures-compl-fa',
    'heures-compl-fc',
    'heures-compl-referentiel',
    'heures-primes',
    'total',
    'solde',
];
$dateColumns = [
    'service-date-modification',
    'intervenant-date-naissance',
    'date-cloture-service-realise',
];
$addableColumns = [
    '__total__',
    'heures-ref',
    'service-fi',
    'service-fa',
    'service-fc',
    'service-referentiel',
    'heures-compl-fi',
    'heures-compl-fa',
    'heures-compl-fc',
    'heures-compl-referentiel',
    'heures-primes',
    'total',
];

// récupération des données

$dateExtraction = new \DateTime();
foreach ($data as $d) {
    $sid = $d['SERVICE_ID'] ? $d['SERVICE_ID'] . '_' . $d['PERIODE_ID'] : $d['ID'];
    $sid .= '_' . $d['MOTIF_NON_PAIEMENT_ID'];
    $sid .= '_' . $d['TAG_ID'];

    $ds = [
        '__total__'                 => (float)$d['HEURES'] + (float)$d['HEURES_NON_PAYEES'] + (float)$d['HEURES_REF'] + (float)$d['TOTAL'],
        'type-etat'                 => $d['TYPE_ETAT'],
        'date'                      => $dateExtraction,
        'service-date-modification' => $d['SERVICE_DATE_MODIFICATION'],
        'annee-libelle'             => (string)$options['annee'],

        'intervenant-code'               => $d['INTERVENANT_CODE'],
        'intervenant-nom'                => $d['INTERVENANT_NOM'],
        'intervenant-date-naissance'     => $d['INTERVENANT_DATE_NAISSANCE'],
        'intervenant-statut-libelle'     => $d['INTERVENANT_STATUT_LIBELLE'],
        'intervenant-type-libelle'       => $d['INTERVENANT_TYPE_LIBELLE'],
        'intervenant-grade-code'         => $d['INTERVENANT_GRADE_CODE'],
        'intervenant-grade-libelle'      => $d['INTERVENANT_GRADE_LIBELLE'],
        'intervenant-discipline-code'    => $d['INTERVENANT_DISCIPLINE_CODE'],
        'intervenant-discipline-libelle' => $d['INTERVENANT_DISCIPLINE_LIBELLE'],
        'heures-service-statutaire'      => (float)$d['SERVICE_STATUTAIRE'],
        'heures-service-du-modifie'      => (float)$d['SERVICE_DU_MODIFIE'],
        'service-structure-aff-libelle'  => $d['SERVICE_STRUCTURE_AFF_LIBELLE'],

        'service-structure-ens-libelle' => $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
        'groupe-type-formation-libelle' => $d['GROUPE_TYPE_FORMATION_LIBELLE'],
        'type-formation-libelle'        => $d['TYPE_FORMATION_LIBELLE'],
        'etape-niveau'                  => empty($d['ETAPE_NIVEAU']) ? null : (int)$d['ETAPE_NIVEAU'],
        'etape-code'                    => $d['ETAPE_CODE'],
        'etape-etablissement-libelle'   => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : ($d['SERVICE_REF_FORMATION'] ? $d['SERVICE_REF_FORMATION'] : $d['ETABLISSEMENT_LIBELLE']),
        'element-code'                  => $d['ELEMENT_CODE'],
        'element-fonction-libelle'      => $d['ELEMENT_LIBELLE'] ? $d['ELEMENT_LIBELLE'] : $d['FONCTION_REFERENTIEL_LIBELLE'],
        'element-discipline-code'       => $d['ELEMENT_DISCIPLINE_CODE'],
        'element-discipline-libelle'    => $d['ELEMENT_DISCIPLINE_LIBELLE'],
        'element-taux-fi'               => (float)$d['ELEMENT_TAUX_FI'],
        'element-taux-fc'               => (float)$d['ELEMENT_TAUX_FC'],
        'element-taux-fa'               => (float)$d['ELEMENT_TAUX_FA'],
        'commentaires'                  => $d['COMMENTAIRES'],
        'element-ponderation-compl'     => $d['ELEMENT_PONDERATION_COMPL'] === null ? null : (float)$d['ELEMENT_PONDERATION_COMPL'],
        'element-source-libelle'        => $d['ELEMENT_SOURCE_LIBELLE'],

        'periode-libelle'              => $d['PERIODE_LIBELLE'],
        'heures-non-payees-libelle'    => $d['MOTIF_NON_PAIEMENT'],
        'tag'                          => $d['TAG'],

        // types d'intervention traités en aval
        'heures-ref'                   => (float)$d['HEURES_REF'],
        'service-fi'                   => (float)$d['SERVICE_FI'],
        'service-fa'                   => (float)$d['SERVICE_FA'],
        'service-fc'                   => (float)$d['SERVICE_FC'],
        'service-referentiel'          => (float)$d['SERVICE_REFERENTIEL'],
        'heures-compl-fi'              => (float)$d['HEURES_COMPL_FI'],
        'heures-compl-fa'              => (float)$d['HEURES_COMPL_FA'],
        'heures-compl-fc'              => (float)$d['HEURES_COMPL_FC'],
        'heures-compl-referentiel'     => (float)$d['HEURES_COMPL_REFERENTIEL'],
        'heures-primes'                => (float)$d['HEURES_PRIMES'],
        'total'                        => (float)$d['TOTAL'],
        'solde'                        => (float)$d['SOLDE'],
        'date-cloture-service-realise' => $d['DATE_CLOTURE_REALISE'],
    ];

    if ($ds['heures-service-du-modifie'] != 0) {
        $ds['__total__']++; // pour que les modifs de service apparaissent
    }

    if ($d['TYPE_INTERVENTION_ID'] != null) {
        $tid = $d['TYPE_INTERVENTION_ID'];
        if (!isset($typesIntervention[$tid])) {
            $typesIntervention[$tid] = $entityManager->getRepository(TypeIntervention::class)->find($tid);
        }
        $typeIntervention = $typesIntervention[$tid];
        $invertTi['type-intervention-' . $typeIntervention->getCode()] = $typeIntervention->getId();
        $ds['type-intervention-' . $typeIntervention->getCode()] = (float)$d['HEURES'];
    }
    foreach ($ds as $column => $value) {
        if (!isset($shown[$column])) $shown[$column] = 0;
        if (is_float($value)) {
            $shown[$column] += $value;
        } else {
            $shown[$column] += empty($value) ? 0 : 1;
        }
    }
    if (!isset($res[$sid])) {
        $res[$sid] = $ds;
    } else {
        foreach ($ds as $column => $value) {
            if (in_array($column, $addableColumns) || 0 === strpos($column, 'type-intervention-')) {
                if (!isset($res[$sid][$column])) {
                    $res[$sid][$column] = $value;
                } // pour les types d'intervention non initialisés
                else {
                    if (is_numeric($value)) {
                        $res[$sid][$column] += $value;
                    } elseif (is_string($value) && $value) {
                        if (isset($res[$sid][$column]) && $res[$sid][$column]) {
                            $res[$sid][$column] .= ', ';
                        }
                        $res[$sid][$column] .= $value;
                    }
                }
            } elseif ($value != $res[$sid][$column]) {
                $res[$sid][$column] = null;
            }
        }
    }
}

// tri et préparation des entêtes
$head = [
    'type-etat'                 => 'Type État',
    'date'                      => 'Date d\'extraction',
    'annee-libelle'             => 'Année universitaire',
    'service-date-modification' => 'Date de modif. du service',

    'intervenant-code'               => 'Code intervenant',
    'intervenant-nom'                => 'Intervenant',
    'intervenant-date-naissance'     => 'Date de naissance',
    'intervenant-statut-libelle'     => 'Statut intervenant',
    'intervenant-type-libelle'       => 'Type d\'intervenant',
    'intervenant-grade-code'         => 'Grade (Code)',
    'intervenant-grade-libelle'      => 'Grade',
    'intervenant-discipline-code'    => 'Discipline intervenant (Code)',
    'intervenant-discipline-libelle' => 'Discipline intervenant',
    'heures-service-statutaire'      => 'Service statutaire',
    'heures-service-du-modifie'      => 'Modification de service du',
    'service-structure-aff-libelle'  => 'Structure d\'affectation',

    'service-structure-ens-libelle' => 'Structure d\'enseignement',
    'groupe-type-formation-libelle' => 'Groupe de type de formation',
    'type-formation-libelle'        => 'Type de formation',
    'etape-niveau'                  => 'Niveau',
    'etape-code'                    => 'Code formation',
    'etape-etablissement-libelle'   => 'Formation ou établissement',
    'element-code'                  => 'Code enseignement',
    'element-fonction-libelle'      => 'Enseignement ou fonction référentielle',
    'element-discipline-code'       => 'Discipline ens. (Code)',
    'element-discipline-libelle'    => 'Discipline ens.',
    'element-taux-fi'               => 'Taux FI',
    'element-taux-fc'               => 'Taux FC',
    'element-taux-fa'               => 'Taux FA',
    'commentaires'                  => 'Commentaires',
    'element-ponderation-compl'     => 'Majoration',
    'element-source-libelle'        => 'Source enseignement',
    'periode-libelle'               => 'Période',
    'heures-non-payees-libelle'     => 'Motif de non paiement',
    'tag'                           => 'Tags',

];
uasort($typesIntervention, function ($ti1, $ti2) {
    return ($ti1->getOrdre() - $ti2->getOrdre()) ? 1 : -1;
});
foreach ($typesIntervention as $typeIntervention) {
    /* @var \OffreFormation\Entity\Db\TypeIntervention $typeIntervention */
    $head['type-intervention-' . $typeIntervention->getCode()] = $typeIntervention->getCode();
}
$head['heures-ref'] = 'Référentiel';
$head['service-fi'] = 'HETD Service FI';
$head['service-fa'] = 'HETD Service FA';
$head['service-fc'] = 'HETD Service FC';
$head['service-referentiel'] = 'HETD Service Référentiel';
$head['heures-compl-fi'] = 'HETD Compl. FI';
$head['heures-compl-fa'] = 'HETD Compl. FA';
$head['heures-compl-fc'] = 'HETD Compl. FC';
$head['heures-compl-referentiel'] = 'HETD Compl. référentiel';
$head['heures-primes'] = 'Prime FC D714-60';
$head['total'] = 'Total HETD';
$head['solde'] = 'Solde HETD';
$head['date-cloture-service-realise'] = 'Clôture du service réalisé';

// suppression des informations superflues
foreach ($shown as $column => $visibility) {
    if (isset($head[$column]) && empty($visibility)) {
        unset($head[$column]);
        if (isset($invertTi[$column])) {
            unset($typesIntervention[$invertTi[$column]]);
        }
    }
}
$columns = array_keys($head);
foreach ($res as $sid => $d) {
    if (0 == $d['__total__']) {
        unset($res[$sid]); // pas d'affichage pour quelqu'un qui n'a rien
    } else {
        $res[$sid] = [];
        foreach ($columns as $column) {
            $value = isset($d[$column]) ? $d[$column] : null;
            if (null === $value && (in_array($column, $numericColunms) || 0 === strpos($column, 'type-intervention-'))) {
                $value = 0;
            }

            if (in_array($column, $dateColumns)) {
                if (empty($value)) $value = null; else $value = \DateTime::createFromFormat('Y-m-d', substr($value, 0, 10));
            }

            $res[$sid][$column] = $value;
        }
    }
}

$csv->setHeader($head);
$csv->addLines($res);