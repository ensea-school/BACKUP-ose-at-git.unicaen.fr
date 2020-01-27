<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$formuleTestIntervenantId = 106;

$data = "901	CM	100,00 %	0,00 %	0,00 %		Oui	200	1,5 HETD	1,5 HETD	300 HETD		76,92 %	295,38 HETD	98,46 %	1,54 %	4,62 HETD		300 HETD	0 h	0 HETD		300 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
909	TD	100,00 %	0,00 %	0,00 %		Oui	80	1 HETD	1 HETD	80 HETD		20,51 %	78,77 HETD	98,46 %	1,54 %	1,23 HETD		380 HETD	0 h	0 HETD		80 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
901	TP	100,00 %	0,00 %	0,00 %		Oui	10	1 HETD	0,67 HETD	10 HETD		2,56 %	9,85 HETD	98,46 %	1,54 %	0,1 HETD		384 HETD	6 h	4 HETD		4 HETD	0 HETD	0 HETD	0 HETD	4 HETD	0 HETD	0 HETD	0 HETD	0 HETD
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
                'C1'   => 2,
                '909'  => 2,
                'C2'   => 3,
                '920'  => 3,
                'C3'   => 4,
                '970'  => 5,
                'UNIV' => 9,
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
            $serviceFi              = stringToFloat(substr($c[22], 0, -5));
            $serviceFa              = stringToFloat(substr($c[23], 0, -5));
            $serviceFc              = stringToFloat(substr($c[24], 0, -5));
            $serviceReferentiel     = stringToFloat(substr($c[25], 0, -5));
            $heuresComplFi          = stringToFloat(substr($c[26], 0, -5));
            $heuresComplFa          = stringToFloat(substr($c[27], 0, -5));
            $heuresComplFc          = stringToFloat(substr($c[28], 0, -5));
            $heuresComplFcMaj       = stringToFloat(substr($c[29], 0, -5));
            $heuresComplReferentiel = stringToFloat(substr($c[30], 0, -5));

            // Transformations
            if ($typeIntervention == 'Référentiel') $typeIntervention = 'REFERENTIEL';

            $referentiel = $typeIntervention == 'REFERENTIEL';
            $tauxFi      = stringToFloat(substr($tauxFi, 0, -1)) / 100;
            $tauxFa      = stringToFloat(substr($tauxFa, 0, -1)) / 100;
            $tauxFc      = stringToFloat(substr($tauxFc, 0, -1)) / 100;

            if ('' == $modulateurHC) $modulateurHC = 1;

            //$heures = substr($heures, -2);
            $heures = stringToFloat($heures);


            $debug = false;
//            $debug = true;

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
