<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Mission\Service\MissionService $smi */
$smi = $container->get(\Mission\Service\MissionService::class);

$intervenant = $container->get(\Application\Service\IntervenantService::class)->get(1000000000);

$guid = '31-20230309-0900-1031-0-0';
$guid = '31-20230308-1400-1700-0-0';

$missionSuivi = $smi->suivi($intervenant, $guid);


//$missionSuivi->setDescription('test');
//$missionSuivi->setHeures(10);

$data = \Application\Controller\Plugin\Axios::extract($missionSuivi);

var_dump($data);

foreach( $missionSuivi->getVolumesHoraires() as $vhm ){
    $vhSpec = $vhm->axiosDefinition();
    $vhSpec[] = 'guid';
    $vhSpec[] = 'heureDebut';
    $vhSpec[] = 'horaireFin';
    $vhSpec[] = 'description';
    $vhSpec[] = 'nocturne';
    $vhSpec[] = 'formation';

    $vhData = \Application\Controller\Plugin\Axios::extract($vhm, $vhSpec);
    unset($vhData['histoCreateur']);
    unset($vhData['histoCreation']);
    unset($vhData['canValider']);
    unset($vhData['canDevalider']);
    unset($vhData['canSupprimer']);
    var_dump($vhData);
}


?>
<script>

    let date = new Date();



    console.log(Util.affDate(date, Util.FORMAT_DATE));

</script>
