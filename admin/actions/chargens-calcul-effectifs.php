<?php

$bdd = $oa->getBdd();

$sql = "
SELECT
  sne.id scenario_noeud_effectif_id,
  sne.effectif effectif,
  sn.noeud_id,
  sn.scenario_id,
  sne.type_heures_id,
  sne.etape_id,
  n.annee_id
FROM
  scenario_noeud_effectif sne
  JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
  JOIN noeud n ON n.id = sn.noeud_id
WHERE
  n.etape_id IS NOT NULL
  AND COALESCE(sne.effectif_calcule,-1) <> sne.effectif
ORDER BY 
  n.annee_id DESC
";


$c->begin("Charges d\'enseignement : calcul de tous les effectifs des noeuds");
$c->msg("Attention : ce traitement peut être long.");


$snes  = $bdd->select($sql);
$count = count($snes);
foreach ($snes as $index => $sne) {
    $sneId      = (int)$sne['SCENARIO_NOEUD_EFFECTIF_ID'];
    $effectif   = (float)$sne['EFFECTIF'];
    $noeud      = (int)$sne['NOEUD_ID'];
    $scenario   = (int)$sne['SCENARIO_ID'];
    $typeHeures = (int)$sne['TYPE_HEURES_ID'];
    $etape      = (int)$sne['ETAPE_ID'];
    $annee      = ((int)$sne['ANNEE_ID']) . '/' . ((int)$sne['ANNEE_ID'] + 1);

    $c->msg("Calcul de la formation $index / $count (année $annee)...", true);

    $bdd->exec("BEGIN OSE_CHARGENS.CALC_SUB_EFFECTIF( $noeud, $scenario, $typeHeures, $etape ); END;");
    $bdd->getTable('SCENARIO_NOEUD_EFFECTIF')->update(['EFFECTIF_CALCULE' => $effectif], ['ID' => $sneId]);
}

$c->end("Fin du calcul");