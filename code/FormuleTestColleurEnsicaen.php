<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$formuleTestIntervenantId = 124;

$data = "901	CM	100,00%	0,00 %	0,00 %		Oui	12 h	1,5 HETD	1,5 HETD	18 HETD		6,02 %	11,56 HETD	64,21 %	35,79 %	6,44 HETD		11,56 HETD	0 HETD	0 HETD	0 HETD	6,44 HETD	0 HETD	0 HETD	0 HETD	0 HETD
909	CM	100,00%	0,00 %	0,00 %		Oui	20 h	1,5 HETD	1,5 HETD	30 HETD		10,03 %	19,26 HETD	64,21 %	35,79 %	10,74 HETD		19,26 HETD	0 HETD	0 HETD	0 HETD	10,74 HETD	0 HETD	0 HETD	0 HETD	0 HETD
901	TD	0,00%	100,00 %	0,00 %		Oui	20 h	1 HETD	1 HETD	20 HETD		6,69 %	12,84 HETD	64,21 %	35,79 %	7,16 HETD		0 HETD	12,84 HETD	0 HETD	0 HETD	0 HETD	7,16 HETD	0 HETD	0 HETD	0 HETD
901	CM	100,00%	0,00 %	0,00 %		Oui	15 h	1,5 HETD	1,5 HETD	22,5 HETD		7,53 %	14,45 HETD	64,21 %	35,79 %	8,05 HETD		14,45 HETD	0 HETD	0 HETD	0 HETD	8,05 HETD	0 HETD	0 HETD	0 HETD	0 HETD
ENSI	Référentiel	100,00%	0,00 %			Oui	10 h	1 HETD	1 HETD	10 HETD		3,34 %	6,42 HETD	64,21 %	35,79 %	3,58 HETD		0 HETD	0 HETD	0 HETD	6,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD	3,58 HETD
ENSI	Référentiel	100,00%	0,00 %			Oui	5 h	1 HETD	1 HETD	5 HETD		1,67 %	3,21 HETD	64,21 %	35,79 %	1,79 HETD		0 HETD	0 HETD	0 HETD	3,21 HETD	0 HETD	0 HETD	0 HETD	0 HETD	1,79 HETD
901	Référentiel	100,00%	0,00 %			Oui	3 h	1 HETD	1 HETD	3 HETD		1,00 %	1,93 HETD	64,21 %	35,79 %	1,07 HETD		0 HETD	0 HETD	0 HETD	1,93 HETD	0 HETD	0 HETD	0 HETD	0 HETD	1,07 HETD
902	CM	0,00%	100,00 %	0,00 %		Oui	15 h	1,5 HETD	1,5 HETD	22,5 HETD		7,53 %	14,45 HETD	64,21 %	35,79 %	8,05 HETD		0 HETD	14,45 HETD	0 HETD	0 HETD	0 HETD	8,05 HETD	0 HETD	0 HETD	0 HETD
902	FC	0,00%	0,00 %	100,00 %		Oui	20 h	2,25 HETD	2,25 HETD	45 HETD		15,05 %	28,9 HETD	64,21 %	35,79 %	16,1 HETD		0 HETD	0 HETD	28,9 HETD	0 HETD	0 HETD	0 HETD	16,1 HETD	0 HETD	0 HETD
902	TD	0,00%	0,00 %	100,00 %		Oui	20 h	1 HETD	1 HETD	20 HETD		6,69 %	12,84 HETD	64,21 %	35,79 %	7,16 HETD		0 HETD	0 HETD	12,84 HETD	0 HETD	0 HETD	0 HETD	7,16 HETD	0 HETD	0 HETD
902	CM	0,00%	0,00 %	100,00 %		Oui	10 h	1,5 HETD	1,5 HETD	15 HETD		5,02 %	9,63 HETD	64,21 %	35,79 %	5,37 HETD		0 HETD	0 HETD	9,63 HETD	0 HETD	0 HETD	0 HETD	5,37 HETD	0 HETD	0 HETD
902	FC	0,00%	0,00 %	100,00 %		Oui	12 h	2,25 HETD	2,25 HETD	27 HETD		9,03 %	17,34 HETD	64,21 %	35,79 %	9,66 HETD		0 HETD	0 HETD	17,34 HETD	0 HETD	0 HETD	0 HETD	9,66 HETD	0 HETD	0 HETD
901	TP	0,00%	0,00 %	100,00 %		Oui	5 h	1 HETD	0,67 HETD	5 HETD		1,67 %	3,21 HETD	64,21 %	35,79 %	1,19 HETD		0 HETD	0 HETD	3,21 HETD	0 HETD	0 HETD	0 HETD	1,19 HETD	0 HETD	0 HETD
901	CM	0,00%	100,00 %	0,00 %		Oui	2 h	1,5 HETD	1,5 HETD	3 HETD		1,00 %	1,93 HETD	64,21 %	35,79 %	1,07 HETD		0 HETD	1,93 HETD	0 HETD	0 HETD	0 HETD	1,07 HETD	0 HETD	0 HETD	0 HETD
901	CM	0,00%	0,00 %	100,00 %		Oui	2 h	1,5 HETD	1,5 HETD	3 HETD		1,00 %	1,93 HETD	64,21 %	35,79 %	1,07 HETD		0 HETD	0 HETD	1,93 HETD	0 HETD	0 HETD	0 HETD	1,07 HETD	0 HETD	0 HETD
901	TP	100,00%	0,00 %	0,00 %		Oui	3 h	1 HETD	0,67 HETD	3 HETD		1,00 %	1,93 HETD	64,21 %	35,79 %	0,72 HETD		1,93 HETD	0 HETD	0 HETD	0 HETD	0,72 HETD	0 HETD	0 HETD	0 HETD	0 HETD
901	FC	0,00%	0,00 %	100,00 %		Oui	10 h	2,25 HETD	2,25 HETD	22,5 HETD		7,53 %	14,45 HETD	64,21 %	35,79 %	8,05 HETD		0 HETD	0 HETD	14,45 HETD	0 HETD	0 HETD	0 HETD	8,05 HETD	0 HETD	0 HETD
901	TP	0,00%	0,00 %	100,00 %		Oui	12 h	1 HETD	0,67 HETD	12 HETD		4,01 %	7,71 HETD	64,21 %	35,79 %	2,86 HETD		0 HETD	0 HETD	7,71 HETD	0 HETD	0 HETD	0 HETD	2,86 HETD	0 HETD	0 HETD
901	CM	100,00%	0,00 %	0,00 %		Oui	5 h	1,5 HETD	1,5 HETD	7,5 HETD		2,51 %	4,82 HETD	64,21 %	35,79 %	2,68 HETD		4,82 HETD	0 HETD	0 HETD	0 HETD	2,68 HETD	0 HETD	0 HETD	0 HETD	0 HETD
901	TD	100,00%	0,00 %	0,00 %		Oui	5 h	1 HETD	1 HETD	5 HETD		1,67 %	3,21 HETD	64,21 %	35,79 %	1,79 HETD		3,21 HETD	0 HETD	0 HETD	0 HETD	1,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
";

/*
Structures :
1	Droit
2	Histoire
3	IAE
4	IUT
5	Lettres
6	Santé
7	Sciences
8	SUAPS
9	Université
*/
$data = explode("\n", $data);
?>
<style>
    table {
        font-size: 8pt;
    }
</style>
<table class="table table-bordered table-condensed table-extra-condensed table-hover">
    <tr>
        <th>Composante</th>
        <th>Service statutaire</th>
        <th>Référentiel</th>
        <th>Type d'intervention</th>
        <th>Taux FI</th>
        <th>Taux FA</th>
        <th>Taux FC</th>
        <th>Modulateur HC</th>
        <th>Heures</th>
        <th>SFi</th>
        <th>SFa</th>
        <th>SFc</th>
        <th>SRef</th>
        <th>HCFi</th>
        <th>HCFa</th>
        <th>HCFc</th>
        <th>HCFcM</th>
        <th>HCRef</th>
        <th>Tableau</th>
    </tr>
    <?php

    /** @var \Doctrine\ORM\EntityManager $bdd */
    $bdd = $container->get(\Application\Constants::BDD);
    /** @var \Application\Service\FormuleTestIntervenantService $ftiService */
    $ftiService = $container->get(\Application\Service\FormuleTestIntervenantService::class);
    $fti        = $ftiService->get($formuleTestIntervenantId);

    $bdd->getConnection()->exec('DELETE FROM formule_test_volume_horaire WHERE intervenant_test_id = ' . $formuleTestIntervenantId);
    foreach ($data as $l) {
        if (trim($l)) {
            $c = explode("\t", trim($l));

            $correspStructs = [
                'P1'   => 1,
                '901'  => 1,
                'FDS'  => 1,
                'C1'   => 2,
                '909'  => 2,
                'C2'   => 3,
                '920'  => 3,
                'C3'   => 4,
                '902'  => 4,
                'UNIV' => 9,
                'ENSI' => 9,
            ];

            $composante             = $correspStructs[$c[0]];
            $serviceStatutaire      = strtolower($c[6]) == 'oui';
            $typeIntervention       = $c[1];
            $referentiel            = false;
            $tauxFi                 = $c[2];
            $tauxFa                 = $c[3];
            $tauxFc                 = $c[4];
            $modulateurHC           = $c[5];
            $heures                 = $c[7];
            $serviceFi              = stringToFloat(substr($c[18], 0, -5));
            $serviceFa              = stringToFloat(substr($c[19], 0, -5));
            $serviceFc              = stringToFloat(substr($c[20], 0, -5));
            $serviceReferentiel     = stringToFloat(substr($c[21], 0, -5));
            $heuresComplFi          = stringToFloat(substr($c[22], 0, -5));
            $heuresComplFa          = stringToFloat(substr($c[23], 0, -5));
            $heuresComplFc          = stringToFloat(substr($c[24], 0, -5));
            $heuresComplFcMaj       = stringToFloat(substr($c[25], 0, -5));
            $heuresComplReferentiel = stringToFloat(substr($c[26], 0, -5));

            // Transformations
            if ($typeIntervention == 'Référentiel') $typeIntervention = 'REFERENTIEL';

            $referentiel = $typeIntervention == 'REFERENTIEL';
            $tauxFi      = (float)str_replace(',', '.', substr($tauxFi, 0, -1)) / 100;
            $tauxFa      = (float)str_replace(',', '.', substr($tauxFa, 0, -1)) / 100;
            $tauxFc      = (float)str_replace(',', '.', substr($tauxFc, 0, -1)) / 100;

            if ('' == $modulateurHC) $modulateurHC = 1;

            $heures = substr($heures, 0, -2);
            $heures = stringToFloat($heures);


            $debug = false;
            //$debug = true;

            // Traitement et affichage
            $composante = $container->get(\Application\Constants::BDD)->getRepository(\Application\Entity\Db\FormuleTestStructure::class)->find($composante);
            if ($debug) {
                $c = '<pre>' . var_export($c, true) . '</pre>';
            } else {
                $c  = '';
                $vh = new \Application\Entity\Db\FormuleTestVolumeHoraire();
                $vh->setIntervenantTest($fti);
                $vh->setStructureTest($composante);
                $vh->setServiceStatutaire($serviceStatutaire);
                $vh->setReferentiel($referentiel);
                $vh->setTypeInterventionCode($typeIntervention);
                $vh->setTauxFi($tauxFi);
                $vh->setTauxFa($tauxFa);
                $vh->setTauxFc($tauxFc);
                $vh->setPonderationServiceCompl($modulateurHC);
                $vh->setHeures($heures);
                $vh->setAServiceFi($serviceFi);
                $vh->setAServiceFa($serviceFa);
                $vh->setAServiceFc($serviceFc);
                $vh->setAServiceReferentiel($serviceReferentiel);
                $vh->setAHeuresComplFi($heuresComplFi);
                $vh->setAHeuresComplFa($heuresComplFa);
                $vh->setAHeuresComplFc($heuresComplFc);
                $vh->setAHeuresComplFcMajorees($heuresComplFcMaj);
                $vh->setAHeuresComplReferentiel($heuresComplReferentiel);
                $bdd->persist($vh);
                $bdd->flush($vh);
            }

            ?>
            <tr>
                <td><?= $composante ?></td>
                <td><?= $serviceStatutaire ? 'Oui' : 'Non' ?></td>
                <td><?= $referentiel ? 'Oui' : 'Non' ?></td>
                <td><?= $typeIntervention ?></td>
                <td><?= $tauxFi ?></td>
                <td><?= $tauxFa ?></td>
                <td><?= $tauxFc ?></td>
                <td><?= $modulateurHC ?></td>
                <td><?= $heures ?></td>
                <td><?= $serviceFi ?></td>
                <td><?= $serviceFa ?></td>
                <td><?= $serviceFc ?></td>
                <td><?= $serviceReferentiel ?></td>
                <td><?= $heuresComplFi ?></td>
                <td><?= $heuresComplFa ?></td>
                <td><?= $heuresComplFc ?></td>
                <td><?= $heuresComplFcMaj ?></td>
                <td><?= $heuresComplReferentiel ?></td>

                <td><?= $c ?></td>
            </tr>

            <?php
        }
    }
    ?>
</table>
