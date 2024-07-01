<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Application\Service\IntervenantService $serviceIntervenant */
$serviceIntervenant = $container->get(\Application\Service\IntervenantService::class);

/** @var \Application\Service\FormuleTestIntervenantService $serviceFormuleTest */
$serviceFormuleTest = $container->get(\Application\Service\FormuleTestIntervenantService::class);

/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(\Doctrine\ORM\EntityManager::class);

$typeVolumeHoraire = $container->get(\Service\Service\TypeVolumeHoraireService::class)->getRealise();
$etatVolumeHoraire = $container->get(\Service\Service\EtatVolumeHoraireService::class)->getSaisi();

$supprExistant = false; // OK
$creationTests = false; // OK
$duplicationDonnees = false; // OK

$dataIntervenants = false; // OK
$dataVolumesHoraires = false; // OK
$dataStructures = false; // OK

$calculTout = false;
$peuplerAttendus = false;
$transfertIntervenantsDansNouvelleInfra = false;
$transfertVolumesHorairesDansNouvelleInfra = true;


$bdd = OseAdmin::instance()->getBdd();
$ti = $bdd->getTable('FORMULE_TEST_INTERVENANT');
$tvh = $bdd->getTable('FORMULE_TEST_VOLUME_HORAIRE');


if ($creationTests) {
    $selectSql = "
    SELECT 
      annee_id, intervenant_id FROM (
    SELECT
      i.annee_id, i.id intervenant_id,
      rank() OVER (PARTITION BY i.annee_id ORDER BY i.id) num
    FROM
      formule_resultat fr
      JOIN intervenant i ON i.id = fr.intervenant_id
    WHERE
      fr.type_volume_horaire_id = " . $typeVolumeHoraire->getId() . " 
      AND fr.etat_volume_horaire_id = " . $etatVolumeHoraire->getId() . " 
      AND fr.service_du > 0
      AND (heures_compl_fa > 0 OR heures_compl_fc > 0)
      AND heures_compl_referentiel > 0
    ) t
    WHERE 
      num <= 999999999999
      AND annee_id > 205     
    ";

    if ($supprExistant) {
        $em->getConnection()->executeQuery('TRUNCATE TABLE formule_test_volume_horaire');
        $em->getConnection()->executeQuery('TRUNCATE TABLE formule_test_intervenant');
    }

    $data = $em->getConnection()->fetchAllAssociative($selectSql);

    $i = 1;
    $nbr = count($data);
    foreach ($data as $d) {
        $anneeId = (int)$d['ANNEE_ID'];
        $intervenantId = (int)$d['INTERVENANT_ID'];

        $intervenant = $serviceIntervenant->get($intervenantId);
        echo "Traitement $i / $nbr : Année $anneeId, Intervenant $intervenantId ...\n";

        try {
            $test = $serviceFormuleTest->creerDepuisIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

            $test->setLibelle($intervenant->getId() . ' - ' . $test->getLibelle());
            $test->setParam5($intervenant->getId());
            $serviceFormuleTest->save($test);
        } catch (\Exception $e) {
            echo 'EREUR : ' . $e->getMessage() . "\n";
        }
        $i++;
    }
}

if ($duplicationDonnees) {
    // on prend les tests et on les duplique pour toutes les formules, niv. intervenant & VH
    $iSql = "
    INSERT INTO formule_test_intervenant (
      ID,
      LIBELLE, FORMULE_ID, ANNEE_ID,
      TYPE_INTERVENANT_ID, TYPE_VOLUME_HORAIRE_ID, ETAT_VOLUME_HORAIRE_ID,
      HEURES_SERVICE_STATUTAIRE, HEURES_SERVICE_MODIFIE, DEPASSEMENT_SERVICE_DU_SANS_HC,
      PARAM_1, PARAM_2, PARAM_3, PARAM_4, PARAM_5,
      A_SERVICE_DU, C_SERVICE_DU,
      TAUX_TP_SERVICE_DU, TAUX_CM_SERVICE_DU, TAUX_CM_SERVICE_COMPL, TAUX_TP_SERVICE_COMPL,
      STRUCTURE_CODE,
      TAUX_AUTRE_1_CODE, TAUX_AUTRE_1_SERVICE_COMPL, TAUX_AUTRE_1_SERVICE_DU,
      TAUX_AUTRE_2_CODE, TAUX_AUTRE_2_SERVICE_COMPL, TAUX_AUTRE_2_SERVICE_DU,
      TAUX_AUTRE_3_CODE, TAUX_AUTRE_3_SERVICE_COMPL, TAUX_AUTRE_3_SERVICE_DU,
      TAUX_AUTRE_4_CODE, TAUX_AUTRE_4_SERVICE_COMPL, TAUX_AUTRE_4_SERVICE_DU,
      TAUX_AUTRE_5_CODE, TAUX_AUTRE_5_SERVICE_COMPL, TAUX_AUTRE_5_SERVICE_DU
    ) 
    SELECT
      ftest_intervenant_id_seq.nextval,
      fti.LIBELLE, f.id FORMULE_ID, fti.ANNEE_ID,
      fti.TYPE_INTERVENANT_ID, fti.TYPE_VOLUME_HORAIRE_ID, fti.ETAT_VOLUME_HORAIRE_ID,
      fti.HEURES_SERVICE_STATUTAIRE, fti.HEURES_SERVICE_MODIFIE, fti.DEPASSEMENT_SERVICE_DU_SANS_HC,
      fti.PARAM_1, fti.PARAM_2, fti.PARAM_3, fti.id PARAM_4, fti.PARAM_5,
      fti.A_SERVICE_DU, fti.C_SERVICE_DU,
      fti.TAUX_TP_SERVICE_DU, fti.TAUX_CM_SERVICE_DU, fti.TAUX_CM_SERVICE_COMPL, fti.TAUX_TP_SERVICE_COMPL,
      fti.STRUCTURE_CODE,
      fti.TAUX_AUTRE_1_CODE, fti.TAUX_AUTRE_1_SERVICE_COMPL, fti.TAUX_AUTRE_1_SERVICE_DU,
      fti.TAUX_AUTRE_2_CODE, fti.TAUX_AUTRE_2_SERVICE_COMPL, fti.TAUX_AUTRE_2_SERVICE_DU,
      fti.TAUX_AUTRE_3_CODE, fti.TAUX_AUTRE_3_SERVICE_COMPL, fti.TAUX_AUTRE_3_SERVICE_DU,
      fti.TAUX_AUTRE_4_CODE, fti.TAUX_AUTRE_4_SERVICE_COMPL, fti.TAUX_AUTRE_4_SERVICE_DU,
      fti.TAUX_AUTRE_5_CODE, fti.TAUX_AUTRE_5_SERVICE_COMPL, fti.TAUX_AUTRE_5_SERVICE_DU
    FROM
      formule_test_intervenant fti
      JOIN formule f ON f.id <> fti.formule_id
    WHERE
      fti.param_4 IS NULL
    ";

    echo "Multiplication du jeu des intervenants par les formules\n";
    $em->getConnection()->executeQuery($iSql);

    $vhSql = "
    INSERT INTO formule_test_volume_horaire (
      ID, 
      INTERVENANT_TEST_ID,
      REFERENTIEL, SERVICE_STATUTAIRE,
      TAUX_FI, TAUX_FA, TAUX_FC,
      TYPE_INTERVENTION_CODE,
      PONDERATION_SERVICE_DU, PONDERATION_SERVICE_COMPL,
      PARAM_1, PARAM_2, PARAM_3, PARAM_4, PARAM_5,
      HEURES,
      C_SERVICE_FI, C_SERVICE_FA, C_SERVICE_FC, C_SERVICE_REFERENTIEL,
      C_HEURES_COMPL_FI, C_HEURES_COMPL_FA, C_HEURES_COMPL_FC, C_HEURES_COMPL_FC_MAJOREES, C_HEURES_COMPL_REFERENTIEL,
      STRUCTURE_CODE
    )
    SELECT
      ftest_volume_horaire_id_seq.nextval, 
      i2.id INTERVENANT_TEST_ID,
      vh.REFERENTIEL, vh.SERVICE_STATUTAIRE,
      vh.TAUX_FI, vh.TAUX_FA, vh.TAUX_FC,
      vh.TYPE_INTERVENTION_CODE,
      vh.PONDERATION_SERVICE_DU, vh.PONDERATION_SERVICE_COMPL,
      vh.PARAM_1, vh.PARAM_2, vh.PARAM_3, vh.id, vh.PARAM_5,
      vh.HEURES,
      vh.C_SERVICE_FI, vh.C_SERVICE_FA, vh.C_SERVICE_FC, vh.C_SERVICE_REFERENTIEL,
      vh.C_HEURES_COMPL_FI, vh.C_HEURES_COMPL_FA, vh.C_HEURES_COMPL_FC, vh.C_HEURES_COMPL_FC_MAJOREES, vh.C_HEURES_COMPL_REFERENTIEL,
      vh.STRUCTURE_CODE
    FROM
      formule_test_volume_horaire vh
      JOIN formule_test_intervenant i ON i.param_4 IS NULL AND i.id = vh.intervenant_test_id
      JOIN formule_test_intervenant i2 ON i2.param_4 = i.id
    WHERE
      vh.param_4 IS NULL
    ";

    echo "Multiplication du jeu des volumes horaires par les formules\n";
    $em->getConnection()->executeQuery($vhSql);
}


if ($dataIntervenants) {
    $sql = "
    
    SELECT
      fi.id,
      CASE WHEN si.code IN ('ENS_CH','ASS_MI_TPS','ENS_CH_LRU','DOCTOR') THEN 'oui' ELSE 'non' END param_1,
      CASE WHEN si.code IN ('LECTEUR','ATER') THEN 'oui' ELSE 'non' END param_2
    FROM
      formule_test_intervenant fi 
      JOIN formule f ON f.id = fi.formule_id AND f.package_name = 'FORMULE_UBO'
      JOIN intervenant i ON i.id = fi.param_5
      JOIN statut si ON si.id = i.statut_id
    
    UNION ALL
    
    SELECT
      fi.id,
      si.code param_1,
      null param_2
    FROM
      formule_test_intervenant fi 
      JOIN formule f ON f.id = fi.formule_id AND f.package_name = 'FORMULE_PARIS1'
      JOIN intervenant i ON i.id = fi.param_5
      JOIN statut si ON si.id = i.statut_id
    
    ";

    echo 'Mise en place des paramètres adaptés au niveau des intervenants' . "\n";
    $q = $bdd->selectEach($sql);
    while ($d = $q->next()) {
        $id = $d['ID'];
        unset($d['ID']);
        $ti->update($d, ['ID' => $id]);
        echo '.';
    }
}

if ($dataVolumesHoraires) {
    $sqls = [
        'Avignon' => "
        SELECT
          fvh.id,
          CASE tf.source_code WHEN '22' THEN 'Oui' ELSE 'Non' END param_1,
          p.code param_2
        FROM
          formule_test_volume_horaire fvh
          JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
          JOIN formule f ON f.id = fi.formule_id AND f.package_name = 'FORMULE_AVIGNON'
          LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
          LEFT JOIN periode p ON p.id = vh.periode_id
          LEFT JOIN service s ON s.id = vh.service_id
          LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN etape e ON e.id = ep.etape_id
          LEFT JOIN type_formation tf ON tf.id = e.type_formation_id        
        ",

        'Lille' => "
        SELECT
          fvh.id,
          null param_1,
          CASE WHEN fr.code like 'D%' THEN 'RP_' || fr.code ELSE fr.code END param_2
        FROM
          formule_test_volume_horaire fvh
          JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
          JOIN formule f ON f.id = fi.formule_id AND f.package_name = 'FORMULE_LILLE'
          LEFT JOIN volume_horaire_ref vhr ON vhr.id = fvh.param_5 AND fvh.referentiel = 1
          LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
          
          LEFT JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
          LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
        ",

        'Paris1' => "
            SELECT
      fvh.id,
      CASE WHEN COALESCE(gtf.libelle_court,'') = 'DU' THEN 'Oui' ELSE 'Non' END param_1,
      CASE 
        WHEN COALESCE(tfr.code,fr.code) like 'C%' THEN 'A3 :' || COALESCE(tfr.code,fr.code)
        WHEN COALESCE(tfr.code,fr.code) like 'D%' THEN 'A3 : APP' || COALESCE(tfr.code,fr.code)
        WHEN COALESCE(tfr.code,fr.code) like 'P%' THEN 'A2 :' || COALESCE(tfr.code,fr.code)
        
        ELSE COALESCE(tfr.code,fr.code)
      END param_2
    FROM
      formule_test_volume_horaire fvh
      JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
      JOIN formule f ON f.id = fi.formule_id AND f.package_name = 'FORMULE_PARIS1'
      LEFT JOIN volume_horaire_ref vhr ON vhr.id = fvh.param_5 AND fvh.referentiel = 1
      LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
      
      LEFT JOIN service s ON s.id = vh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN etape e ON e.id = ep.etape_id
      LEFT JOIN type_formation tf ON tf.id = e.type_formation_id
      LEFT JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
      LEFT JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
      LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
      LEFT JOIN fonction_referentiel tfr ON tfr.id = tfr.parent_id
        ",

        'Rouen' => "
            SELECT
              fvh.id,
              CASE 
                WHEN COALESCE(tfr.code,fr.code) like 'C%' then 'G2'
                WHEN COALESCE(tfr.code,fr.code) like 'D%' then 'G3'
                ELSE COALESCE(tfr.code,fr.code)
              END param_1,
              null param_2
            FROM
              formule_test_volume_horaire fvh
              JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
              JOIN formule f ON f.id = fi.formule_id AND f.package_name = 'FORMULE_ROUEN'
              LEFT JOIN volume_horaire_ref vhr ON vhr.id = fvh.param_5 AND fvh.referentiel = 1
              LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
              
              LEFT JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
              LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
              LEFT JOIN fonction_referentiel tfr ON tfr.id = tfr.parent_id
        ",

        'Paris8' => "
            SELECT
              fvh.id,
              CASE 
                WHEN ep.code like 'E%' then 'PRIO' || ep.code
                ELSE ep.code
              END param_1,
              null param_2
            FROM
              formule_test_volume_horaire fvh
              JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
              JOIN formule f ON f.id = fi.formule_id AND f.package_name IN ('FORMULE_PARIS8', 'FORMULE_PARIS8_2021')
              LEFT JOIN volume_horaire_ref vhr ON vhr.id = fvh.param_5 AND fvh.referentiel = 1
              LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
              
              LEFT JOIN service s ON s.id = vh.service_id
              LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
        ",

        'Assas' => "
            SELECT
              fvh.id,
              CASE WHEN e.type_formation_id BETWEEN 30 AND 50 THEN 'Oui' ELSE 'Non' END param_1,
              null param_2
            FROM
              formule_test_volume_horaire fvh
              JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
              JOIN formule f ON f.id = fi.formule_id AND f.package_name IN ('FORMULE_ASSAS')
              LEFT JOIN volume_horaire_ref vhr ON vhr.id = fvh.param_5 AND fvh.referentiel = 1
              LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
              
              LEFT JOIN service s ON s.id = vh.service_id
            LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
            LEFT JOIN etape e ON e.id = ep.etape_id
        ",

        'Rennes2' => "
            SELECT
              fvh.id,
              CASE WHEN src.id IS NOT NULL THEN 'Oui' ELSE 'Non' END param_1,
              e.fi + e.fc + e.fa param_2
            FROM
              formule_test_volume_horaire fvh
              JOIN formule_test_intervenant fi ON fi.id = fvh.intervenant_test_id
              JOIN formule f ON f.id = fi.formule_id AND f.package_name IN ('FORMULE_RENNES2')
              LEFT JOIN volume_horaire_ref vhr ON vhr.id = fvh.param_5 AND fvh.referentiel = 1
              LEFT JOIN volume_horaire vh ON vh.id = fvh.param_5 AND fvh.referentiel = 0
              
              LEFT JOIN service s ON s.id = vh.service_id
              LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
              LEFT JOIN source src ON src.id = ep.source_id AND LOWER(src.code) = 'apogee'
              LEFT JOIN effectifs e ON e.element_pedagogique_id = ep.id AND e.histo_destruction IS NULL
        ",
    ];

    echo 'Mise en place des paramètres adaptés au niveau des volumes horaires' . "\n";
    foreach ($sqls as $formule => $sql) {
        echo $formule . "\n";
        $q = $bdd->selectEach($sql);
        while ($d = $q->next()) {
            $id = $d['ID'];
            unset($d['ID']);
            $tvh->update($d, ['ID' => $id]);
            echo '.';
        }
    }

}

if ($dataStructures) {

    $sqli = "
    SELECT
      ft.id, cc.ns_code structure_code
    FROM
    (         SELECT 'FORMULE_DAUPHINE' f, '15'  os_code, 'DEP'        ns_code FROM dual
      UNION ALL SELECT 'FORMULE_GUYANE'   f, '15'  os_code, 'ES3'        ns_code FROM dual
      UNION ALL SELECT 'FORMULE_LYON2'    f, '15'  os_code, 'D4DAC10000' ns_code FROM dual
      UNION ALL SELECT 'FORMULE_NANTERRE' f, '15'  os_code, 'UP8'        ns_code FROM dual
      UNION ALL SELECT 'FORMULE_NANTERRE' f, 'I13' os_code, 'KE10'       ns_code FROM dual
      UNION ALL SELECT 'FORMULE_PARIS8'   f, '15'  os_code, '20595'      ns_code FROM dual
      UNION ALL SELECT 'FORMULE_PARIS8'   f, 'I13' os_code, '40'         ns_code FROM dual
      UNION ALL SELECT 'FORMULE_POITIERS' f, '15'  os_code, 'I2000'      ns_code FROM dual
      UNION ALL SELECT 'FORMULE_POITIERS' f, 'I13' os_code, 'I2300'      ns_code FROM dual
      ) cc
      JOIN formule f ON f.package_name = cc.f
      JOIN formule_test_intervenant ft ON ft.formule_id = f.id AND ft.structure_code = cc.os_code
    ";

    echo 'Adaptation des codes structures pour les intervenants' . "\n";
    $q = $bdd->selectEach($sqli);
    while ($d = $q->next()) {
        $id = $d['ID'];
        unset($d['ID']);
        $ti->update($d, ['ID' => $id]);
        echo '.';
    }

    $sqlVh = "
    SELECT
      ftv.id, cc.ns_code structure_code
    FROM
    (         SELECT 'FORMULE_DAUPHINE' f, '15'  os_code, 'DEP'        ns_code FROM dual
      UNION ALL SELECT 'FORMULE_GUYANE'   f, '15'  os_code, 'ES3'        ns_code FROM dual
      UNION ALL SELECT 'FORMULE_LYON2'    f, '15'  os_code, 'D4DAC10000' ns_code FROM dual
      UNION ALL SELECT 'FORMULE_NANTERRE' f, '15'  os_code, 'UP8'        ns_code FROM dual
      UNION ALL SELECT 'FORMULE_NANTERRE' f, 'I13' os_code, 'KE10'       ns_code FROM dual
      UNION ALL SELECT 'FORMULE_PARIS8'   f, '15'  os_code, '20595'      ns_code FROM dual
      UNION ALL SELECT 'FORMULE_PARIS8'   f, 'I13' os_code, '40'         ns_code FROM dual
      UNION ALL SELECT 'FORMULE_POITIERS' f, '15'  os_code, 'I2000'      ns_code FROM dual
      UNION ALL SELECT 'FORMULE_POITIERS' f, 'I13' os_code, 'I2300'      ns_code FROM dual
      ) cc
      JOIN formule f ON f.package_name = cc.f
      JOIN formule_test_intervenant ft ON ft.formule_id = f.id 
      JOIN formule_test_volume_horaire ftv ON ftv.intervenant_test_id = ft.id AND ftv.structure_code = cc.os_code
    ";

    echo 'Adaptation des codes structures pour les volumes horaires' . "\n";
    $q = $bdd->selectEach($sqlVh);
    while ($d = $q->next()) {
        $id = $d['ID'];
        unset($d['ID']);
        $tvh->update($d, ['ID' => $id]);
        echo '.';
    }
}


// on calcule tout!
/*
 * Exemples erreurs

ERREUR ERREUR ERREUR ERREUR id = 160535 - 1629 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_ST_ETIENNE", ligne 277
ERREUR ERREUR ERREUR ERREUR id = 160538 - 1632 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_INSA_LYON", ligne 416
ERREUR ERREUR ERREUR ERREUR id = 160539 - 1633 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_POITIERS_2021", ligne 338
ERREUR ERREUR ERREUR ERREUR id = 160541 - 1635 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_ARTOIS", ligne 426
ERREUR ERREUR ERREUR ERREUR id = 160542 - 1636 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_PARIS", ligne 370
ERREUR ERREUR ERREUR ERREUR id = 160543 - 1637 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_LILLE", ligne 284
ERREUR ERREUR ERREUR ERREUR id = 160544 - 1638 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_REUNION_2022", ligne 338
ERREUR ERREUR ERREUR ERREUR id = 160545 - 1639 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_SORBONNE_NOUVELLE", ligne 474
ERREUR ERREUR ERREUR ERREUR id = 160547 - 1641 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_GUYANE", ligne 758
ERREUR ERREUR ERREUR ERREUR id = 160548 - 1642 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_RENNES1", ligne 318
ERREUR ERREUR ERREUR ERREUR id = 160549 - 1643 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_UVSQ", ligne 329
ERREUR ERREUR ERREUR ERREUR id = 160550 - 1644 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_SACLAY", ligne 270
ERREUR ERREUR ERREUR ERREUR id = 160551 - 1645 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_ROUEN_2022", ligne 332
ERREUR ERREUR ERREUR ERREUR id = 160553 - 1647 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_PARIS1", ligne 354
ERREUR ERREUR ERREUR ERREUR id = 160555 - 1649 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_ASSAS", ligne 414
ERREUR ERREUR ERREUR ERREUR id = 160556 - 1650 / 85566 ORA-20001: ORA-06512: à "OSE.FORMULE_DAUPHINE", ligne 502
 */
if ($calculTout) {
    $min = 0;
    $min = 139968;

    echo "Calcul de tous les tests\n";
    $sql = "SELECT count(*) c FROM formule_test_intervenant WHERE id >= $min";
    $count = (int)$bdd->selectOne($sql, [], 'C');
    $i = 0;
    $sql = "SELECT id FROM formule_test_intervenant WHERE id >= $min ORDER BY id";
    $q = $bdd->select($sql);
    foreach ($q as $fti) {
        $i++;
        try {
            $calcSql = "BEGIN ose_formule.test(" . $fti['ID'] . "); END;";
            $bdd->exec($calcSql);
            echo 'Calcul test id = ' . $fti['ID'] . " - $i / $count\n";
        } catch (\Exception $e) {
            echo 'ERREUR ERREUR ERREUR ERREUR id = ' . $fti['ID'] . " - $i / $count " . $e->getMessage() . "\n";
        }
    }
}

if ($peuplerAttendus) {
    $cols = [
        'SERVICE_FI',
        'SERVICE_FA',
        'SERVICE_FC',
        'SERVICE_REFERENTIEL',
        'HEURES_COMPL_FI',
        'HEURES_COMPL_FA',
        'HEURES_COMPL_FC',
        'HEURES_COMPL_FC_MAJOREES',
        'HEURES_COMPL_REFERENTIEL'
    ];

    foreach ($cols as $col) {
        echo "Enregistrement des valeurs attendues pour les colonnes de type $col ...\n";
        $sql = "UPDATE formule_test_volume_horaire SET A_" . "$col = C_" . "$col WHERE COALESCE(A_" . "$col, -9999) <> COALESCE(C_" . "$col, -9999)";
        $em->getConnection()->executeQuery($sql);
    }
}

if ($transfertIntervenantsDansNouvelleInfra) {
    $bddDevConfig = OseAdmin::instance()->config()->get('bdds')['local-dev'];
    $bddDev = new \Unicaen\BddAdmin\Bdd($bddDevConfig);
    $bddDev->setLogger(OseAdmin::instance()->console());

    $intervenantsSql = "
    SELECT
      fti.ID, fti.LIBELLE, fti.ANNEE_ID, 
      fti.TYPE_INTERVENANT_ID, fti.TYPE_VOLUME_HORAIRE_ID, fti.ETAT_VOLUME_HORAIRE_ID, 
      fti.HEURES_SERVICE_STATUTAIRE, fti.HEURES_SERVICE_MODIFIE, fti.DEPASSEMENT_SERVICE_DU_SANS_HC,
      fti.PARAM_1, fti.PARAM_2, fti.PARAM_3, fti.PARAM_4, fti.PARAM_5,
      fti.C_SERVICE_DU SERVICE_DU, 
      fti.TAUX_TP_SERVICE_DU, fti.TAUX_CM_SERVICE_DU, fti.TAUX_CM_SERVICE_COMPL, fti.TAUX_TP_SERVICE_COMPL, 
      fti.STRUCTURE_CODE,
      fti.TAUX_AUTRE_1_CODE, fti.TAUX_AUTRE_1_SERVICE_COMPL, fti.TAUX_AUTRE_1_SERVICE_DU, 
      fti.TAUX_AUTRE_2_CODE, fti.TAUX_AUTRE_2_SERVICE_COMPL, fti.TAUX_AUTRE_2_SERVICE_DU,
      fti.TAUX_AUTRE_3_CODE, fti.TAUX_AUTRE_3_SERVICE_COMPL, fti.TAUX_AUTRE_3_SERVICE_DU, 
      fti.TAUX_AUTRE_4_CODE, fti.TAUX_AUTRE_4_SERVICE_COMPL, fti.TAUX_AUTRE_4_SERVICE_DU,
      fti.TAUX_AUTRE_5_CODE, fti.TAUX_AUTRE_5_SERVICE_COMPL, fti.TAUX_AUTRE_5_SERVICE_DU,
      CASE 
            WHEN f.package_name = 'FORMULE_UNICAEN' AND fti.annee_id <= 2016 THEN 'FORMULE_UNICAEN_2016'
            WHEN f.package_name = 'FORMULE_PARIS8' AND fti.annee_id <= 2021 THEN 'FORMULE_PARIS8_2021'
            WHEN f.package_name = 'FORMULE_POITIERS' AND fti.annee_id <= 2021 THEN 'FORMULE_POITIERS_2021'
            WHEN f.package_name = 'FORMULE_ULHN' AND fti.annee_id <= 2021 THEN 'FORMULE_ULHN_2021'
            WHEN f.package_name = 'FORMULE_REUNION' AND fti.annee_id <= 2022 THEN 'FORMULE_REUNION_2022'
            WHEN f.package_name = 'FORMULE_UPEC' AND fti.annee_id <= 2022 THEN 'FORMULE_UPEC_2022'
            ELSE f.package_name
          END formule_code
    FROM
      formule_test_intervenant fti
      JOIN formule f ON f.id = fti.FORMULE_ID
    ";


    if ($supprExistant) {
        echo "Vidage des tables FORMULE_TEST_VOLUME_HORAIRE & FORMULE_TEST_INTERVENANT\n";
        $bddDev->getTable('FORMULE_TEST_VOLUME_HORAIRE')->truncate();
        $bddDev->getTable('FORMULE_TEST_INTERVENANT')->truncate();
    }

    $formules = $bddDev->getTable('FORMULE')->select(null, ['key' => 'CODE']);

    $iTable = $bddDev->getTable('FORMULE_TEST_INTERVENANT');

    $count = (int)$bdd->selectOne('SELECT count(*) C from FORMULE_TEST_INTERVENANT', [], 'C');
    $i = 0;
    $r = $em->getConnection()->executeQuery($intervenantsSql);
    $bddDev->beginTransaction();
    while ($d = $r->fetchAssociative()) {
        $d['FORMULE_ID'] = (int)$formules[$d['FORMULE_CODE']]['ID'];
        unset($d['FORMULE_CODE']);
        $iTable->insert($d);
        $i++;
        echo "Transfert des intervenants $i / $count\n";
    }
    $bddDev->commitTransaction();
}


if ($transfertVolumesHorairesDansNouvelleInfra) {
    $bddDevConfig = OseAdmin::instance()->config()->get('bdds')['local-dev'];
    $bddDev = new \Unicaen\BddAdmin\Bdd($bddDevConfig);
    $bddDev->setLogger(OseAdmin::instance()->console());

    $volumesHorairesSql = "
    SELECT
      v.ID,
      v.INTERVENANT_TEST_ID FORMULE_INTERVENANT_TEST_ID,
      v.REFERENTIEL,
      v.SERVICE_STATUTAIRE,
      v.TAUX_FI,
      v.TAUX_FA,
      v.TAUX_FC,
      v.TYPE_INTERVENTION_CODE,
      v.PONDERATION_SERVICE_DU,
      v.PONDERATION_SERVICE_COMPL,
      v.PARAM_1,
      v.PARAM_2,
      v.PARAM_3,
      v.PARAM_4,
      v.PARAM_5,
      v.HEURES,
      COALESCE(v.C_SERVICE_FI,0) HEURES_ATTENDUES_SERVICE_FI,
      COALESCE(v.C_SERVICE_FA,0) HEURES_ATTENDUES_SERVICE_FA,
      COALESCE(v.C_SERVICE_FC,0) HEURES_ATTENDUES_SERVICE_FC,
      COALESCE(v.C_SERVICE_REFERENTIEL,0) HEURES_ATTENDUES_SERVICE_REFERENTIEL,
      COALESCE(v.C_HEURES_COMPL_FI,0) HEURES_ATTENDUES_COMPL_FI,
      COALESCE(v.C_HEURES_COMPL_FA,0) HEURES_ATTENDUES_COMPL_FA,
      COALESCE(v.C_HEURES_COMPL_FC,0) HEURES_ATTENDUES_COMPL_FC,
      COALESCE(v.C_HEURES_COMPL_FC_MAJOREES,0) HEURES_ATTENDUES_PRIMES,
      COALESCE(v.C_HEURES_COMPL_REFERENTIEL,0) HEURES_ATTENDUES_COMPL_REFERENTIEL,
      COALESCE(v.C_SERVICE_FI,0) HEURES_SERVICE_FI,
      COALESCE(v.C_SERVICE_FA,0) HEURES_SERVICE_FA,
      COALESCE(v.C_SERVICE_FC,0) HEURES_SERVICE_FC,
      COALESCE(v.C_SERVICE_REFERENTIEL,0) HEURES_SERVICE_REFERENTIEL,
      COALESCE(v.C_HEURES_COMPL_FI,0) HEURES_COMPL_FI,
      COALESCE(v.C_HEURES_COMPL_FA,0) HEURES_COMPL_FA,
      COALESCE(v.C_HEURES_COMPL_FC,0) HEURES_COMPL_FC,
      COALESCE(v.C_HEURES_COMPL_FC_MAJOREES,0) HEURES_PRIMES,
      COALESCE(v.C_HEURES_COMPL_REFERENTIEL,0) HEURES_COMPL_REFERENTIEL,
      v.STRUCTURE_CODE STRUCTURE_CODE
    FROM
      formule_test_volume_horaire v
      JOIN formule_test_intervenant i ON i.id = v.intervenant_test_id
    WHERE
      i.annee_id = :annee AND i.formule_id = :formuleId
    ";

    $sqlPrepa = "
    select 
      i.annee_id, 
      f.id formule_id,
      f.package_name formule,
      count(*) c
    from 
      formule_test_intervenant i
      JOIN formule f ON f.id = i.formule_id
    group by 
      annee_id,
      f.id,
      f.package_name
    ORDER BY
      annee_id, f.package_name
    ";


    if ($supprExistant) {
        echo "Vidage de la table FORMULE_TEST_VOLUME_HORAIRE\n";
        $bddDev->getTable('FORMULE_TEST_VOLUME_HORAIRE')->truncate();
    }

    $zones = $bdd->select($sqlPrepa);
    $vhTable = $bddDev->getTable('FORMULE_TEST_VOLUME_HORAIRE');

    foreach ($zones as $zone) {
        $annee = (int)$zone['ANNEE_ID'];
        $formule = $zone['FORMULE'];
        $formuleId = $zone['FORMULE_ID'];
        $count = $zone['C'];
        echo "Transfert des volumes horaires, $annee, $formule\n";
        $i = 0;
        $vhs = $bdd->select($volumesHorairesSql, ['annee' => $annee, 'formuleId' => $formuleId]);
        $bddDev->beginTransaction();
        foreach ($vhs as $d) {
            $i++;
            $d['HEURES_ATTENDUES_NON_PAYABLE_FI'] = 0;
            $d['HEURES_ATTENDUES_NON_PAYABLE_FA'] = 0;
            $d['HEURES_ATTENDUES_NON_PAYABLE_FC'] = 0;
            $d['HEURES_ATTENDUES_NON_PAYABLE_REFERENTIEL'] = 0;
            $d['HEURES_NON_PAYABLE_FI'] = 0;
            $d['HEURES_NON_PAYABLE_FA'] = 0;
            $d['HEURES_NON_PAYABLE_FC'] = 0;
            $d['HEURES_NON_PAYABLE_REFERENTIEL'] = 0;
            $d['NON_PAYABLE'] = 0;
            try {
                $vhTable->insert($d);
                OseAdmin::instance()->console()->msg("Injection des volumes horaires $i / $count", true);
            }catch(\Exception $e){
                echo "Erreur ERREUR ERREUR $i / $count\n";
                echo $e->getMessage();
                die();
            }


        }
        $bddDev->commitTransaction();
    }
}