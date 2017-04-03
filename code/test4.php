<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */


/** @var \Application\Provider\Chargens\ChargensProvider $s */
$s  = $sl->get('chargens');

/** @var \Application\Service\Etape $se */
$se = $sl->get('applicationEtape');

$data = file_get_contents('/home/laurent/data.csv');
$data = explode( "\n", $data );

$sql = [];

$thids = [
    'fi' => 1,
    'fa' => 2,
    'fc' => 3,
];

foreach( $data as $d ){
    if ($d != ''){
        $d = explode( "\t", $d);

        $code = trim($d[0]);
        $e = [];
        $e['fi'] = (int)trim($d[9]);
        $e['fc'] = (int)trim($d[10]);
        $e['fa'] = (int)trim($d[11]);
        //var_dump($code, $fi, $fc, $fa);

        $etape = $se->getRepo()->findOneBy([
            'sourceCode' => $code,
            'annee' => $se->getServiceContext()->getAnnee(),
        ]);

        if ($etape){
            $etapeId = $etape->getId();

            foreach( $e as $ec => $eff ){
                if ($eff > 0){
                    $thid = $thids[$ec];
                    $sql[] = "OSE_CHARGENS.INIT_SCENARIO_NOEUD_EFFECTIF($etapeId,12,$thid, $eff, TRUE );";
                }
            }


        }else{
//            var_dump('étape non trouvée : '.$code);
        }
    }
}

echo implode( "<br />\n", $sql );