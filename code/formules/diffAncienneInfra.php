<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */


function calculAttendus()
{
    $min = 143143;

    $bdd = AppAdmin::container()->get(\Unicaen\BddAdmin\Bdd::class);

    echo "Calcul de tous les tests\n";
    $sql   = "SELECT count(*) c FROM formule_test_intervenant WHERE id >= $min";
    $count = (int)$bdd->selectOne($sql, [], 'C');
    $i     = 0;
    $sql   = "SELECT id FROM formule_test_intervenant WHERE id >= $min ORDER BY id";
    $q     = $bdd->select($sql);
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


function calculTests(string $formuleName)
{
    $bdd = AppAdmin::container()->get(\Unicaen\BddAdmin\Bdd::class);


    $testService = AppAdmin::container()->get(\Formule\Service\TestService::class);
    $formulator = AppAdmin::container()->get(\Formule\Service\FormulatorService::class);

    $selectSql = "
    SELECT
      i.id, i.libelle, f.code, i.annee_id
    FROM
      formule_test_intervenant i
      JOIN formule f ON f.id = i.formule_id
    WHERE
      f.code = '$formuleName'
    ORDER BY
      i.id
    ";

    $count       = $bdd->selectOne("SELECT count(*) C FROM ($selectSql) t", [], 'C');
    $numero      = 0;
    $selectQuery = $bdd->selectEach($selectSql);
    while ($d = $selectQuery->next()) {
        $numero++;
        $testId  = (int)$d['ID'];
        $libelle = $d['LIBELLE'];
        $formule = $d['CODE'];
        $annee   = $d['ANNEE_ID'];

        $calculText = "Calcul $numero/$count, id=$testId, $annee, $formule, $libelle";

        $test = $testService->get($testId);
        try {
            $formulator->calculer($test);

            if (isDiffAC($test)) {
                $testService->calculerAttendu($test);
                $formulator->calculer($test);
                $testService->save($test);
            }
            if ($msg = isDiffAC($test)) {
                echo $calculText . "\n";
                echo "DIFF " . $msg . "\n";
                $testService->save($test);
            } else {
                echo $calculText . "\n";
                //$testService->save($test);
            }

        } catch (\Throwable $e) {
            echo 'ERREUR : ' . $calculText . "\n";
            echo 'ERREUR : ' . $e->getMessage() . "\n";
        }
    }
    echo 'Test terminé pour ' . $formuleName . '!!                                                                             ' . "\n";
}


function isDiffAC(\Formule\Entity\Db\FormuleTestIntervenant $test): ?string
{
    $methods = [
        'ServiceFi',
        'ServiceFa',
        'ServiceFc',
        'ServiceReferentiel',
        'ComplFi',
        'ComplFa',
        'ComplFc',
        'ComplReferentiel',
        'Primes',
    ];

    $diff = false;
    foreach ($test->getVolumesHoraires() as $vh) {
        /** @var $vh \Formule\Entity\Db\FormuleTestVolumeHoraire */

        foreach ($methods as $m) {
            $mc = 'getHeures' . $m;
            $ma = 'getHeuresAttendues' . $m;

            $hc = round($vh->$mc(), 2);
            $ha = round($vh->$ma(), 2);

            $diff = round(abs($hc - $ha), 2);

            if ($diff > 0.01) {
                return "Différence de $diff heures trouvée";
            }
            if ($diff != 0) {
                //return "Différence arrondi";
            }
        }
    }
    return null;
}

try {
    $params = \UnicaenCode\Util::$inputParams;

    $formuleName = $params[2] ?? null;

    calculTests($formuleName);

} catch (\Throwable $e) {
    die('ERREUR :' . $e->getMessage());
}

/*

3707 tests / formule

Erreurs arrondis : 112 au total

FORMULE_ARTOIS : 151553, 186917, 202765, 207805, 232137
FORMULE_ASSAS : 148319, 186931, 193203
FORMULE_AVIGNON : 151230, 204374
FORMULE_COTE_AZUR : 149896, 159108,159780, 160536, 161936, 172408, 173024, 173668, 177700, 178820, 179660, 180052, 180192, 184756, 184952,
            184980, 185736, 186912, 196096, 201388, 206652, 206708, 207744, 208808, 215892, 220764, 226728, 229808, 232412, 240280
FORMULE_GUYANE : 151559, 199299, 202771
FORMULE_LILLE : 148307, 151555, 186919, 199295, 202767, 232139 (package modifié en BQ, BW, CC, CI : param_2 NULL mal géré)
FORMULE_LYON2 : 153562, 199286
FORMULE_MONTPELLIER : 160529
FORMULE_PARIS1 : 148317, 151565, 186929, 193201, 199305, 202777, 232149
FORMULE_PARIS8_2021 : 184256, 186916, 206264, 211752, 226956, 229952 (package corrigé en AG, AS, BE: pb si code enseignement NULL)
FORMULE_PICARDIE : 151564, 202776
FORMULE_REUNION_2022 : 148308, 170512, 186920, 193192, 199296, 205932, 231468
FORMULE_SORBONNE_NOUVELLE : 170513, 199297
FORMULE_UBO : 153560
FORMULE_ULHN : 240274
FORMULE_ULHN_2021 : 149890, 159102, 160530, 161930, 172402, 173018, 173662, 178814, 179654, 180046, 180186, 184750, 184946, 184974, 185730,
            186906, 196090, 201382, 206646, 206702, 207738, 208802, 215886, 226722, 229802, 232406
FORMULE_UNICAEN : 139630, 139694, 139754, 140091
FORMULE_UNICAEN_2020 : 138872, 139209 (Jean-Michel Cador 2018 & 2019)
FORMULE_UPEC_2022 : 153574, 199298

Parfait :

FORMULE_DAUPHINE
FORMULE_INSA_LYON
FORMULE_NANTERRE
FORMULE_PARIS
FORMULE_PARIS8
FORMULE_POITIERS
FORMULE_POITIERS_2021
FORMULE_RENNES1
FORMULE_RENNES2
FORMULE_REUNION
FORMULE_ROUEN      (package modifié pour tenir compte des spécificités du référentiel en param_1)
FORMULE_ROUEN_2022 (package modifié pour régler les pb div/0)
FORMULE_SACLAY
FORMULE_ST_ETIENNE
FORMULE_UNICAEN_2015
FORMULE_UNISTRA
FORMULE_UPEC (package corrigé en AE11, AE12, AE13 : pb somme.si)
FORMULE_UVSQ

*/

