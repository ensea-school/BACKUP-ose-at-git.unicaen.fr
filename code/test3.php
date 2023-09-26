<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \UnicaenTbl\Service\TableauBordService $c */
$c = $container->get(\UnicaenTbl\Service\TableauBordService::class);

$ptbl = $c->getTableauBord('paiement');


$params = [
   'INTERVENANT_ID' => 882254,
    //'INTERVENANT_ID' => 20970,
   // 'FORMULE_RES_SERVICE_ID' => 113957505
    'SERVICE_ID' => 258206,
   //'ANNEE_ID' => 2014,
];




$debut = microtime(true);

$data = $ptbl->getProcess()->getData($params);
echo '<h2>Data en entrée</h2>';
echo phpDump($data);


$ptbl->calculer($params);


$res = phpDump(oseAdmin()->getBdd()->getTable('TBL_PAIEMENT')->select($params));
echo '<h2>Résultat</h2>';
echo phpDump($res);

$fin = microtime(true);
$duree = $fin - $debut;

echo "  Durée : ".round($duree, 4) . ' seconde(s)'."\n";


