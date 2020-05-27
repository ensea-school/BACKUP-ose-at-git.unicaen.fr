<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$formuleTestIntervenantId = 2;

$save = false;
$save = true;

$structureUniversité = 'Université';

$vhdata = "STAPS	Oui	Non	Oui	100,00 %	0,00 %	0,00 %	CM	1,50	1,50	1,00	1,25	40,00							60,00	0,00	0,00	0,00	0,00	0,00	0,00	0,00	0,00		1,50	60,00		60,00	60,00	60,00			0,00	1,88	0,00
STAPS	Oui	Non	Oui	85,00 %	0,00 %	15,00 %	TD	1,00	1,00	1,00	1,00	35,00							29,75	0,00	5,25	0,00	0,00	0,00	0,00	0,00	0,00		1,00	35,00		35,00	95,00	35,00			0,00	1,00	0,00
Langues	Non	Non	Oui	100,00 %	0,00 %	0,00 %	TP	0,67	0,67	1,00	1,00	40,00							26,67	0,00	0,00	0,00	0,00	0,00	0,00	0,00	0,00		0,67	26,67		26,67	121,67	26,67			0,00	0,67	0,00
STAPS	Oui	Non	Oui	100,00 %	0,00 %	0,00 %	Référentiel	1,00	1,00	1,00	1,00	25,00							0,00	0,00	0,00	25,00	0,00	0,00	0,00	0,00	0,00		1,00	0,00		25,00	146,67	25,00			0,00	1,00	0,00
ISSTO	Non	Non	Oui	90,00 %	0,00 %	10,00 %	TP	0,67	0,67	1,00	1,00	30,00							18,00	0,00	2,00	0,00	0,00	0,00	0,00	0,00	0,00		0,67	20,00		20,00	166,67	20,00			0,00	0,67	0,00
Langues	Non	Non	Oui	45,00 %	5,00 %	50,00 %	TD	1,00	1,00	1,40	1,40	20,00							11,40	1,27	12,67	0,00	1,20	0,13	1,33	0,00	0,00		1,40	28,00		28,00	192,00	25,33			1,90	1,40	2,67
Université	Non	Non	Oui	100,00 %	0,00 %	0,00 %	Référentiel	1,00	1,00	1,00	1,00	40,00							0,00	0,00	0,00	0,00	0,00	0,00	0,00	0,00	40,00		1,00	0,00		40,00	192,00	0,00			40,00	1,00	40,00
";


/** @var \Doctrine\ORM\EntityManager $bdd */
$bdd = $container->get(\Application\Constants::BDD);
/** @var \Application\Service\FormuleTestIntervenantService $ftiService */
$ftiService = $container->get(\Application\Service\FormuleTestIntervenantService::class);
$fti        = $ftiService->get($formuleTestIntervenantId);


$lines = explode("\n", $vhdata);
$vhs   = [];
foreach ($lines as $l) {
    if (!empty($l)) {
        $l = explode("\t", $l);

        $structureCode = trim($l[0]);
        $rcode         = \UnicaenApp\Util::reduce($structureCode);
        $isUniv        = ($structureCode == $structureUniversité) || (false !== strpos($rcode, 'univ'));
        if ($isUniv) $structureCode = '__UNIV__';

        $tiCode      = trim($l[7]);
        $referentiel = \UnicaenApp\Util::reduce($tiCode) == 'referentiel';

        $vh = new \Application\Entity\Db\FormuleTestVolumeHoraire();
        $vh->setIntervenantTest($fti);
        $vh->setStructureCode($structureCode);
        $vh->setServiceStatutaire(cBool($l[3]));
        $vh->setTauxFi(cPourc($l[4]));
        $vh->setTauxFa(cPourc($l[5]));
        $vh->setTauxFc(cPourc($l[6]));
        $vh->setTypeInterventionCode($tiCode);
        $vh->setReferentiel($referentiel);
        $vh->setPonderationServiceDu(cFloat($l[10]));
        $vh->setPonderationServiceCompl(cFloat($l[11]));
        $vh->setHeures(cFloat($l[12]));
        $vh->setParam1(trim($l[13]) ?: null);
        $vh->setParam2(trim($l[14]) ?: null);
        $vh->setParam3(trim($l[15]) ?: null);
        $vh->setParam4(trim($l[16]) ?: null);
        $vh->setParam5(trim($l[17]) ?: null);
        $vh->setAServiceFi(cFloat($l[19]));
        $vh->setAServiceFa(cFloat($l[20]));
        $vh->setAServiceFc(cFloat($l[21]));
        $vh->setAServiceReferentiel(cFloat($l[22]));
        $vh->setAHeuresComplFi(cFloat($l[23]));
        $vh->setAHeuresComplFa(cFloat($l[24]));
        $vh->setAHeuresComplFc(cFloat($l[25]));
        $vh->setAHeuresComplFcMajorees(cFloat($l[26]));
        $vh->setAHeuresComplReferentiel(cFloat($l[27]));
        $vhs[] = $vh;
    }
}


?>

    <style>
        table {
            font-size: 8pt;
        }
    </style>
    <table class="table table-bordered table-condensed table-extra-condensed table-hover">
        <tr>
            <th colspan="18">Entrée</th>
            <th colspan="9">Résultats attendus</th>
        </tr>
        <tr>
            <th>Structure code</th>
            <th>Structure is affectation</th>
            <th>Structure is univ</th>
            <th>Service statutaire</th>
            <th>Taux fi</th>
            <th>Taux fa</th>
            <th>Taux fc</th>
            <th>Type intervention code</th>
            <th>Taux service du</th>
            <th>Taux service compl</th>
            <th>Ponderation service du</th>
            <th>Ponderation service compl</th>
            <th>Heures</th>
            <th>Param 1</th>
            <th>Param 2</th>
            <th>Param 3</th>
            <th>Param 4</th>
            <th>Param 5</th>
            <th>SFi</th>
            <th>SFa</th>
            <th>SFc</th>
            <th>SRef</th>
            <th>HCFi</th>
            <th>HCFa</th>
            <th>HCFc</th>
            <th>HCFcM</th>
            <th>HCRef</th>
        </tr>
        <?php foreach ($vhs as $vh):
            if ($vh->getReferentiel()) {
                $tauxServiceDu    = 1;
                $tauxServiceCompl = 1;
            } else {
                switch ($vh->getTypeInterventionCode()) {
                    case 'CM':
                        $tauxServiceDu    = $fti->getTauxCmServiceDu();
                        $tauxServiceCompl = $fti->getTauxCmServiceCompl();
                    break;
                    case 'TD':
                        $tauxServiceDu    = 1;
                        $tauxServiceCompl = 1;
                    break;
                    case 'TP':
                        $tauxServiceDu    = $fti->getTauxTpServiceDu();
                        $tauxServiceCompl = $fti->getTauxTpServiceCompl();
                    break;
                    default:
                        $tauxServiceDu    = $fti->getTauxAutreServiceDu();
                        $tauxServiceCompl = $fti->getTauxAutreServiceCompl();
                }
            }

            ?>
            <tr>
                <td><?= $vh->getStructureCode(); ?></td>
                <td><?= ($vh->getStructureCode() == $fti->getStructureCode()) ? 'Oui' : 'Non'; ?></td>
                <td><?= ($vh->getStructureCode() == '__UNIV__') ? 'Oui' : 'Non' ?></td>
                <td><?= $vh->getServiceStatutaire() ? 'Oui' : 'Non'; ?></td>
                <td><?= floatToString($vh->getTauxFi() * 100); ?>%</td>
                <td><?= floatToString($vh->getTauxFa() * 100); ?>%</td>
                <td><?= floatToString($vh->getTauxFc() * 100); ?>%</td>
                <td><?= $vh->getReferentiel() ? 'Référentiel' : $vh->getTypeInterventionCode(); ?></td>
                <td><?= floatToString($tauxServiceDu); ?></td>
                <td><?= floatToString($tauxServiceCompl); ?></td>
                <td><?= floatToString($vh->getPonderationServiceDu()); ?></td>
                <td><?= floatToString($vh->getPonderationServiceCompl()); ?></td>
                <td><?= floatToString($vh->getHeures()); ?></td>
                <td><?= $vh->getParam1(); ?></td>
                <td><?= $vh->getParam2(); ?></td>
                <td><?= $vh->getParam3(); ?></td>
                <td><?= $vh->getParam4(); ?></td>
                <td><?= $vh->getParam5(); ?></td>
                <td><?= floatToString($vh->getAServiceFi()); ?></td>
                <td><?= floatToString($vh->getAServiceFa()); ?></td>
                <td><?= floatToString($vh->getAServiceFc()); ?></td>
                <td><?= floatToString($vh->getAServiceReferentiel()); ?></td>
                <td><?= floatToString($vh->getAHeuresComplFi()); ?></td>
                <td><?= floatToString($vh->getAHeuresComplFa()); ?></td>
                <td><?= floatToString($vh->getAHeuresComplFc()); ?></td>
                <td><?= floatToString($vh->getAHeuresComplFcMajorees()); ?></td>
                <td><?= floatToString($vh->getAHeuresComplReferentiel()); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php


function cBool($value): bool
{
    return trim(strtolower($value)) == 'oui';
}


function cPourc($value): float
{
    $value = trim(str_replace('%', '', $value));

    return cFloat($value) / 100;
}


function cFloat($value): float
{
    $value = trim($value);
    $value = str_replace(',', '.', $value);

    return (float)$value;
}

if ($save) {
    $bdd->getConnection()->exec('DELETE FROM formule_test_volume_horaire WHERE intervenant_test_id = ' . $formuleTestIntervenantId);
    foreach ($vhs as $vh) {
        $bdd->persist($vh);
        $bdd->flush($vh);
    }
}