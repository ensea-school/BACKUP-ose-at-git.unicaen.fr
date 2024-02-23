<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$intervenantId = 778603;

/** @var  $st \UnicaenTbl\Service\TableauBordService */
$st = $container->get(\UnicaenTbl\Service\TableauBordService::class);


$params = [
    'INTERVENANT_ID' => $intervenantId,
    'TYPE_VOLUME_HORAIRE_ID' => 1,
];

$st->calculer('formule', $params);