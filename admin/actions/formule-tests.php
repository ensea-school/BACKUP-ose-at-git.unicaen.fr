<?php

$bdd = OseAdmin::instance()->getBdd();

$sql = "
  SELECT 
    f.id formule_id, package_name 
  FROM 
    parametre p
    JOIN formule f ON f.id = p.valeur
  WHERE
      p.nom = 'formule'
  ";

$formule = $bdd->selectOne($sql, []);
$formuleId = (int)$formule['FORMULE_ID'];
$package = $formule['PACKAGE_NAME'];

$etatVolumeHoraireId = (int)$bdd->selectOne("SELECT id FROM etat_volume_horaire WHERE code ='saisi'", [], 'ID');

$sql = "
  SELECT
    $package.INTERVENANT_QUERY AS i_query,
    $package.VOLUME_HORAIRE_QUERY AS vh_query
  FROM 
    dual
";

$queries = $bdd->selectOne($sql, []);

$iSql = '
select 
  d.*, 
  ti.id type_intervenant_id 
from (' . trim($queries['I_QUERY']) . ') d JOIN type_intervenant ti ON ti.code = d.type_intervenant_code WHERE d.annee_id = :annee';
$vhSql = 'select d.* from (' . trim($queries['VH_QUERY']) . ') d join intervenant i on i.id = d.intervenant_id WHERE i.annee_id = :annee';

$resSql = "
SELECT 
  i.annee_id                    annee_id,
  fr.intervenant_id             intervenant_id,
  fr.type_volume_horaire_id     type_volume_horaire_id,
  frvh.volume_horaire_id        volume_horaire_id,
  null                          volume_horaire_ref_id,
  frvh.service_fi               service_fi,
  frvh.service_fa               service_fa,
  frvh.service_fc               service_fc,
  0                             service_referentiel,
  frvh.heures_compl_fi          heures_compl_fi,
  frvh.heures_compl_fa          heures_compl_fa,
  frvh.heures_compl_fc          heures_compl_fc,
  0                             heures_compl_referentiel,
  frvh.heures_compl_fc_majorees heures_primes
FROM
  formule_resultat fr
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN formule_resultat_vh frvh ON frvh.formule_resultat_id = fr.id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
WHERE
  evh.code = 'saisi'
  AND i.annee_id = :annee

UNION ALL

SELECT 
  i.annee_id                    annee_id,
  fr.intervenant_id             intervenant_id,
  fr.type_volume_horaire_id     type_volume_horaire_id,
  null                          volume_horaire_id,
  frvh.volume_horaire_ref_id    volume_horaire_ref_id,
  0                             service_fi,
  0                             service_fa,
  0                             service_fc,
  frvh.service_referentiel      service_referentiel,
  0                             heures_compl_fi,
  0                             heures_compl_fa,
  0                             heures_compl_fc,
  frvh.heures_compl_referentiel heures_compl_referentiel,
  0                             heures_primes
FROM
  formule_resultat fr
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN formule_resultat_vh_ref frvh ON frvh.formule_resultat_id = fr.id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
WHERE
  evh.code = 'saisi'
  AND i.annee_id = :annee
";

$tauxCm = $bdd->selectOne("SELECT taux_hetd_service, taux_hetd_complementaire FROM type_intervention WHERE code = 'CM'");
$tauxTp = $bdd->selectOne("SELECT taux_hetd_service, taux_hetd_complementaire FROM type_intervention WHERE code = 'TP'");

$anneesParser = $bdd->selectEach("SELECT DISTINCT annee_id FROM intervenant WHERE histo_destruction IS NULL ORDER BY annee_id");
$annees = [];
while ($annee = $anneesParser->next()) {
    $annees[] = (int)$annee['ANNEE_ID'];
}

OseAdmin::instance()->console()->printMainTitle('Création d\'un jeu de données de test pour la formule "'.$package.'"');

$filename = getcwd().'/cache/'.strtolower($package).'_test.txt';

$file = fopen($filename, 'w');
fwrite($file, '<?php'."\n\n");
fwrite($file, '$annees = '.var_export($annees, true).";\n\n");

$data = [];
$intSeq = 0;
$vhSeq = 0;
foreach ($annees as $annee) {
    OseAdmin::instance()->console()->println('Année ' . $annee);
    $params = ['annee' => $annee];

    $data = [];
    $iParser = $bdd->selectEach($iSql, $params);
    while ($fi = $iParser->next()) {
        $intervenants[(int)$fi['INTERVENANT_ID']] = $fi;
    }

    $vhParser = $bdd->selectEach($vhSql, $params);
    while ($vh = $vhParser->next()) {
        $key = $vh['INTERVENANT_ID'] . '-' . $vh['TYPE_VOLUME_HORAIRE_ID'];
        $intervenant = (int)$vh['INTERVENANT_ID'];

        if (!isset($data[$key])) {
            $intData = $intervenants[$intervenant];
            $intSeq++;
            $data[$key] = [
                'intervenant'     => [
                    'ID'                             => $intSeq,
                    'LIBELLE'                        => $key,
                    'FORMULE_ID'                     => $formuleId,
                    'ANNEE_ID'                       => $annee,
                    'TYPE_INTERVENANT_ID'            => (int)$intData['TYPE_INTERVENANT_ID'],
                    'TYPE_VOLUME_HORAIRE_ID'         => (int)$vh['TYPE_VOLUME_HORAIRE_ID'],
                    'ETAT_VOLUME_HORAIRE_ID'         => $etatVolumeHoraireId,
                    'HEURES_SERVICE_STATUTAIRE'      => (int)$intData['HEURES_SERVICE_STATUTAIRE'],
                    'HEURES_SERVICE_MODIFIE'         => (int)$intData['HEURES_SERVICE_MODIFIE'],
                    'DEPASSEMENT_SERVICE_DU_SANS_HC' => (int)$intData['DEPASSEMENT_SERVICE_DU_SANS_HC'],
                    'PARAM_1'                        => $intData['PARAM_1'],
                    'PARAM_2'                        => $intData['PARAM_2'],
                    'PARAM_3'                        => $intData['PARAM_3'],
                    'PARAM_4'                        => $intData['PARAM_4'],
                    'PARAM_5'                        => $intData['PARAM_5'],
                    'SERVICE_DU'                     => (int)$intData['HEURES_SERVICE_STATUTAIRE'] + (int)$intData['HEURES_SERVICE_MODIFIE'],
                    'TAUX_TP_SERVICE_DU'             => (float)$tauxTp['TAUX_HETD_SERVICE'],
                    'TAUX_CM_SERVICE_DU'             => (float)$tauxCm['TAUX_HETD_SERVICE'],
                    'TAUX_CM_SERVICE_COMPL'          => (float)$tauxCm['TAUX_HETD_COMPLEMENTAIRE'],
                    'TAUX_TP_SERVICE_COMPL'          => (float)$tauxTp['TAUX_HETD_COMPLEMENTAIRE'],
                    'STRUCTURE_CODE'                 => $intData['STRUCTURE_CODE'],
                    'TAUX_AUTRE_1_CODE'              => null,
                    'TAUX_AUTRE_1_SERVICE_COMPL'     => null,
                    'TAUX_AUTRE_1_SERVICE_DU'        => null,
                    'TAUX_AUTRE_2_CODE'              => null,
                    'TAUX_AUTRE_2_SERVICE_COMPL'     => null,
                    'TAUX_AUTRE_2_SERVICE_DU'        => null,
                    'TAUX_AUTRE_3_CODE'              => null,
                    'TAUX_AUTRE_3_SERVICE_COMPL'     => null,
                    'TAUX_AUTRE_3_SERVICE_DU'        => null,
                    'TAUX_AUTRE_4_CODE'              => null,
                    'TAUX_AUTRE_4_SERVICE_COMPL'     => null,
                    'TAUX_AUTRE_4_SERVICE_DU'        => null,
                    'TAUX_AUTRE_5_CODE'              => null,
                    'TAUX_AUTRE_5_SERVICE_COMPL'     => null,
                    'TAUX_AUTRE_5_SERVICE_DU'        => null,
                ],
                'volumesHoraires' => [],
                'taux'            => [],
            ];
        }

        $typeInterventionCode = $vh['TYPE_INTERVENTION_CODE'];
        $tauxId = 0;
        if (!in_array($typeInterventionCode, ['CM', 'TD', 'TP']) && !isset($data[$key]['taux'][$typeInterventionCode])) {
            $tauxId = count($data[$key]['taux']) + 1;
            $data[$key]['taux'][$typeInterventionCode] = [
                'ID'                 => $tauxId,
                'TAUX_SERVICE_DU'    => $vh['TAUX_SERVICE_DU'],
                'TAUX_SERVICE_COMPL' => $vh['TAUX_SERVICE_COMPL'],
            ];
            if ($tauxId <= 5) {
                $data[$key]['intervenant']['TAUX_AUTRE_' . $tauxId . '_CODE'] = $typeInterventionCode;
                $data[$key]['intervenant']['TAUX_AUTRE_' . $tauxId . '_SERVICE_DU'] = $vh['TAUX_SERVICE_DU'];
                $data[$key]['intervenant']['TAUX_AUTRE_' . $tauxId . '_SERVICE_COMPL'] = $vh['TAUX_SERVICE_COMPL'];
            }
        }

        if ($tauxId <= 5) {
            // on n'importe pas les volumes horaires s'il y a + de 5 taux spéciaux : les tests de ofrmule ne le gèrent pas
            $vhSeq++;
            $vhKey = ($vh['VOLUME_HORAIRE_ID'] ?? '0') . '-' . ($vh['VOLUME_HORAIRE_REF_ID'] ?? '0');
            $data[$key]['volumesHoraires'][$vhKey] = [
                'ID'                        => $vhSeq,
                'INTERVENANT_TEST_ID'       => $data[$key]['intervenant']['ID'],
                'REFERENTIEL'               => $vh['VOLUME_HORAIRE_REF_ID'] ? 1 : 0,
                'SERVICE_STATUTAIRE'        => $vh['SERVICE_STATUTAIRE'],
                'TAUX_FI'                   => $vh['TAUX_FI'],
                'TAUX_FA'                   => $vh['TAUX_FA'],
                'TAUX_FC'                   => $vh['TAUX_FC'],
                'TYPE_INTERVENTION_CODE'    => $vh['TYPE_INTERVENTION_CODE'],
                'PONDERATION_SERVICE_DU'    => $vh['PONDERATION_SERVICE_DU'],
                'PONDERATION_SERVICE_COMPL' => $vh['PONDERATION_SERVICE_COMPL'],
                'PARAM_1'                   => $vh['PARAM_1'],
                'PARAM_2'                   => $vh['PARAM_2'],
                'PARAM_3'                   => $vh['PARAM_3'],
                'PARAM_4'                   => $vh['PARAM_4'],
                'PARAM_5'                   => $vh['PARAM_5'],
                'HEURES'                    => $vh['HEURES'],
                'SERVICE_FI'                => 0,
                'SERVICE_FA'                => 0,
                'SERVICE_FC'                => 0,
                'SERVICE_REFERENTIEL'       => 0,
                'HEURES_COMPL_FI'           => 0,
                'HEURES_COMPL_FA'           => 0,
                'HEURES_COMPL_FC'           => 0,
                'HEURES_COMPL_REFERENTIEL'  => 0,
                'HEURES_PRIMES'             => 0,
                'STRUCTURE_CODE'            => $vh['STRUCTURE_CODE'],
                'RES'                       => false,
            ];
        }
    }

    $resParser = $bdd->selectEach($resSql, $params);
    while ($res = $resParser->next()) {
        $key = $res['INTERVENANT_ID'] . '-' . $res['TYPE_VOLUME_HORAIRE_ID'];
        $vhKey = ($res['VOLUME_HORAIRE_ID'] ?? '0') . '-' . ($res['VOLUME_HORAIRE_REF_ID'] ?? '0');
        if (isset($data[$key]['volumesHoraires'][$vhKey])) {
            $data[$key]['volumesHoraires'][$vhKey]['SERVICE_FI'] = $res['SERVICE_FI'];
            $data[$key]['volumesHoraires'][$vhKey]['SERVICE_FA'] = $res['SERVICE_FA'];
            $data[$key]['volumesHoraires'][$vhKey]['SERVICE_FC'] = $res['SERVICE_FC'];
            $data[$key]['volumesHoraires'][$vhKey]['SERVICE_REFERENTIEL'] = $res['SERVICE_REFERENTIEL'];
            $data[$key]['volumesHoraires'][$vhKey]['HEURES_COMPL_FI'] = $res['HEURES_COMPL_FI'];
            $data[$key]['volumesHoraires'][$vhKey]['HEURES_COMPL_FA'] = $res['HEURES_COMPL_FA'];
            $data[$key]['volumesHoraires'][$vhKey]['HEURES_COMPL_FC'] = $res['HEURES_COMPL_FC'];
            $data[$key]['volumesHoraires'][$vhKey]['HEURES_COMPL_REFERENTIEL'] = $res['HEURES_COMPL_REFERENTIEL'];
            $data[$key]['volumesHoraires'][$vhKey]['HEURES_PRIMES'] = $res['HEURES_PRIMES'];
            $data[$key]['volumesHoraires'][$vhKey]['RES'] = true;
        }
    }

    foreach( $data as $key => $d2){
        unset($data[$key]['taux']);
        foreach( $d2['volumesHoraires'] as $vhKey => $vh ){
            if (!$vh['RES']){
                unset($data[$key]['volumesHoraires'][$vhKey]); // Volume horaire non calculé
                OseAdmin::instance()->console()->println('Volume horaire '.$vhKey.' sans résultat');
            }
            unset($data[$key]['volumesHoraires'][$vhKey]['RES']);
        }
        if (empty($data[$key]['volumesHoraires'])) {
            unset($data[$key]); // aucun résultat de formule calculé
            OseAdmin::instance()->console()->println('Intervenant '.$key.' sans résultat');
        }

    }

    fwrite($file, '$data'.$annee.' = '.var_export($data, true).";\n\n");
}

fclose($file);

OseAdmin::instance()->console()->print("Export terminé! Le résultat se trouve dans le fichier suivant :\n\n ".$filename."\n");