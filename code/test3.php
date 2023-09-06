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
   //'INTERVENANT_ID' => 667613
   // 'INTERVENANT_ID' => 20970
    'FORMULE_RES_SERVICE_ID' => 113957505
];




$debut = microtime(true);

$ptbl->getProcess()->test();
//$ptbl->calculer($params);

$fin = microtime(true);
$duree = $fin - $debut;

echo "  Durée : ".round($duree, 4) . ' seconde(s)'."\n";


