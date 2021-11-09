<?php

$bdd = $oa->getBdd();
$bdd->setLogger($c);

$sql = "
SELECT
  sn.noeud_id,
  sn.scenario_id,
  sne.type_heures_id,
  sne.etape_id
FROM
  scenario_noeud_effectif sne
  JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
  JOIN noeud n ON n.id = sn.noeud_id
WHERE
  n.etape_id IS NOT NULL
";


$c->begin("Charges d\'enseignement : calcul de tous les effectifs des noeuds");
$c->msg("Attention : ce traitement peut être long.");


$snes  = $bdd->select($sql);
$count = count($snes);
foreach ($snes as $index => $sne) {
    $noeud      = (int)$sne['NOEUD_ID'];
    $scenario   = (int)$sne['SCENARIO_ID'];
    $typeHeures = (int)$sne['TYPE_HEURES_ID'];
    $etape      = (int)$sne['ETAPE_ID'];

    $c->msg("Calcul de la formation $index / $count ...", true);

    $bdd->exec("BEGIN OSE_CHARGENS.CALC_SUB_EFFECTIF( $noeud, $scenario, $typeHeures, $etape ); END;");
}

$c->end("Fin du calcul");