<?php

$bdd     = $oa->getBdd();
$console = $c;


$console->println("Calcul du tableau de bord piece_jointe_demande");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'piece_jointe_demande\'); END;');

$console->println("Calcul du tableau de bord piece_jointe_fournie");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'piece_jointe_fournie\'); END;');

$console->println("Calcul du tableau de bord piece_jointe");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'piece_jointe\'); END;');

$console->println("");

/* On fournie et on valide de force les PJ qui n'étaient pas tout le temps demandées avant */
$sql   = '
        SELECT
          TPJ.INTERVENANT_ID, TPJ.TYPE_PIECE_JOINTE_ID, I.STRUCTURE_ID, TV.ID TYPE_VALIDATION_ID, P.VALEUR UTILISATEUR_ID
        FROM 
          TBL_PIECE_JOINTE TPJ
          JOIN INTERVENANT I ON I.ID = TPJ.INTERVENANT_ID AND I.HISTO_DESTRUCTION IS NULL
          JOIN TYPE_PIECE_JOINTE_STATUT TPJS ON TPJS.HISTO_DESTRUCTION IS NULL AND TPJS.TYPE_PIECE_JOINTE_ID = TPJ.TYPE_PIECE_JOINTE_ID AND TPJS.STATUT_INTERVENANT_ID = I.STATUT_ID AND TPJS.DUREE_VIE = 99
          JOIN PARAMETRE P ON P.NOM = \'oseuser\'
          JOIN TYPE_VALIDATION TV ON TV.CODE = \'PIECE_JOINTE\'
          LEFT JOIN PIECE_JOINTE PJ ON PJ.TYPE_PIECE_JOINTE_ID = TPJ.TYPE_PIECE_JOINTE_ID AND PJ.INTERVENANT_ID = I.ID AND PJ.HISTO_DESTRUCTION IS NULL
        WHERE 
          TPJ.DEMANDEE = 1 AND TPJ.FOURNIE = 0 AND TPJ.OBLIGATOIRE = 1
          AND I.PREMIER_RECRUTEMENT = 0
          AND PJ.ID IS NULL
        ';
$r     = $bdd->select($sql);
$count = 0;
foreach ($r as $i) {
    $count++;
    $o = ['histo-user-id' => $i['UTILISATEUR_ID']];

    $fichier = [
        'ID'          => $bdd->sequenceNextVal('FICHIER_ID_SEQ'),
        'NOM'         => 'Fichier non fourni',
        'TYPE'        => 'text/plain',
        'TAILLE'      => 0,
        'DESCRIPTION' => 'Fichier non fourni',
    ];
    $bdd->getTable('FICHIER')->insert($fichier, $o);

    $validation = [
        'ID'                 => $bdd->sequenceNextVal('VALIDATION_ID_SEQ'),
        'TYPE_VALIDATION_ID' => $i['TYPE_VALIDATION_ID'],
        'INTERVENANT_ID'     => $i['INTERVENANT_ID'],
        'STRUCTURE_ID'       => $i['STRUCTURE_ID'],
    ];
    $bdd->getTable('VALIDATION')->insert($validation, $o);

    $pieceJointe = [
        'ID'                   => $bdd->sequenceNextVal('PIECE_JOINTE_ID_SEQ'),
        'TYPE_PIECE_JOINTE_ID' => $i['TYPE_PIECE_JOINTE_ID'],
        'INTERVENANT_ID'       => $i['INTERVENANT_ID'],
        'VALIDATION_ID'        => $validation['ID'],
    ];
    $bdd->getTable('PIECE_JOINTE')->insert($pieceJointe, $o);

    $pjf = [
        'PIECE_JOINTE_ID' => $pieceJointe['ID'],
        'FICHIER_ID'      => $fichier['ID'],
    ];
    $bdd->getTable('PIECE_JOINTE_FICHIER')->insert($pjf, $o);
    $console->print("Insertion des pièces jointes manquantes : $count / " . count($r) . "\r");
}

$console->println("Nouveau calcul du tableau de bord piece_jointe_demande");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'piece_jointe_demande\'); END;');

$console->println("Nouveau calcul du tableau de bord piece_jointe_fournie");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'piece_jointe_fournie\'); END;');

$console->println("Nouveau calcul du tableau de bord piece_jointe");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'piece_jointe\'); END;');

$console->println("Nouveau calcul du tableau de bord workflow");
$bdd->exec('BEGIN unicaen_tbl.calculer(\'workflow\'); END;');

$console->println("Terminé");