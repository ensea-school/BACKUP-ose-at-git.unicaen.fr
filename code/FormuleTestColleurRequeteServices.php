<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$data = "2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	1	0	0	1	1	20						22,88	0	0		7,12	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	0,9	0	0,1	1	1	10						10,3	0	0		3,2	0	1,5	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	0,9	0	0,1	1	1	9						9,27	0	0		2,88	0	1,35	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	1	0	0	1	1	35						40,05	0	0		12,45	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	4						3,05	0	0		0,95	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	1	0	0	1	1	12						13,73	0	0		4,27	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	1	0	0	1	1	6						6,87	0	0		2,13	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	1	0	0	1	1	36						41,19	0	0		12,81	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	1,5						1,14	0	0		0,36	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	1,5						1,14	0	0		0,36	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	1,5						1,14	0	0		0,36	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	7						5,34	0	0		1,66	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	9						6,87	0	0		2,13	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	2						1,53	0	0		0,47	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	CM	1,5	1,5	1	0	0	1	1	16						18,31	0	0		5,69	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	1,25						0,95	0	0		0,3	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	0,9	0	0,1	1	1	2						1,37	0	0		0,43	0	0,2	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	0	TD	1	1	1	0	0	1	1	9						6,87	0	0		2,13	0	0	0	
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	1	REFERENTIEL	1	1	0	0	0	1	1	20									0					20
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	1	REFERENTIEL	1	1	0	0	0	1	1	20									0					20
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	1	REFERENTIEL	1	1	0	0	0	1	1	10									0					10
2018	Le Bars	Thierry	Université de Caen	Intervenant permanent	192	0	0	0	Prévisionnel	Validé	1	0	1	1	REFERENTIEL	1	1	0	0	0	1	1	12									0					12
";

$debug = false;
//$debug = true;

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
        <th>Structure</th>
        <th>ServiceStatutaire</th>
        <th>Référentiel</th>
        <th>Type d'intervention</th>
        <th>Taux FI</th>
        <th>Taux FA</th>
        <th>Taux FC</th>
        <th>Modulateur HC</th>
        <th>Heures</th>
        <th>P1</th>
        <th>P2</th>
        <th>P3</th>
        <th>P4</th>
        <th>P5</th>
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

    $fti = null;
    foreach ($data as $l) {
        if (trim($l)) {
            $c = explode("\t", trim($l));

            if (!$fti) {
                $fti = new \Application\Entity\Db\FormuleTestIntervenant();
                $fi  = [
                    'annee'                      => $c[0],
                    'nom'                        => $c[1],
                    'prenom'                     => $c[2],
                    'formule'                    => $c[3],
                    'typeIntervenant'            => $c[4],
                    'heuresServiceStatutaire'    => stringToFloat($c[5]),
                    'depassementServiceDuSansHC' => stringToFloat($c[6]),
                    'heuresServiceModifie'       => stringToFloat($c[7]),
                    'typeVolumeHoraire'          => $c[9],
                    'etatVolumeHoraire'          => $c[10],
                ];
                if ($debug) {
                    var_dump($fi);
                } else {
                    $fti->setLibelle($fi['prenom'] . ' ' . $fi['nom']);
                    $fti->setFormule($bdd->getRepository(\Application\Entity\Db\Formule::class)->findOneBy(['libelle' => $fi['formule']]));
                    $fti->setAnnee($bdd->getRepository(\Application\Entity\Db\Annee::class)->find($fi['annee']));
                    $fti->setTypeIntervenant($bdd->getRepository(\Application\Entity\Db\TypeIntervenant::class)->findOneBy(['libelle' => $fi['typeIntervenant']]));
                    $fti->setStructureTest($bdd->getRepository(\Application\Entity\Db\FormuleTestStructure::class)->find(1));
                    $fti->setTypeVolumeHoraire($bdd->getRepository(\Application\Entity\Db\TypeVolumeHoraire::class)->findOneBy(['libelle' => $fi['typeVolumeHoraire']]));
                    $fti->setEtatVolumeHoraire($bdd->getRepository(\Application\Entity\Db\EtatVolumeHoraire::class)->findOneBy(['libelle' => $fi['etatVolumeHoraire']]));
                    $fti->setHeuresServiceStatutaire($fi['heuresServiceStatutaire']);
                    $fti->setHeuresServiceModifie($fi['heuresServiceModifie']);
                    $fti->setDepassementServiceDuSansHC($fi['depassementServiceDuSansHC'] == '1');
                    $bdd->persist($fti);
                    $bdd->flush($fti);
                }
            }

            $composante             = $c[11] == '1' ? 1 : ($c[12] == '1' ? 9 : 2);
            $serviceStatutaire      = $c[13] == '1';
            $typeIntervention       = $c[15];
            $referentiel            = $c[14] == '1';
            $tauxFi                 = stringToFloat($c[18]);
            $tauxFa                 = stringToFloat($c[19]);
            $tauxFc                 = stringToFloat($c[20]);
            $modulateurHC           = stringToFloat($c[22]);
            $heures                 = stringToFloat($c[23]);
            $param1                 = $c[24];
            $param2                 = $c[25];
            $param3                 = $c[26];
            $param4                 = $c[27];
            $param5                 = $c[28];
            $serviceFi              = stringToFloat($c[29]);
            $serviceFa              = stringToFloat($c[30]);
            $serviceFc              = stringToFloat($c[31]);
            $serviceReferentiel     = stringToFloat($c[32]);
            $heuresComplFi          = stringToFloat($c[33]);
            $heuresComplFa          = stringToFloat($c[34]);
            $heuresComplFc          = stringToFloat($c[35]);
            $heuresComplFcMaj       = stringToFloat($c[36]);
            $heuresComplReferentiel = isset($c[37]) ? stringToFloat($c[37]) : null;

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
                <td><?= $param1 ?></td>
                <td><?= $param2 ?></td>
                <td><?= $param3 ?></td>
                <td><?= $param4 ?></td>
                <td><?= $param5 ?></td>
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
