<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);

$sTbl = $fs->getServiceTableauBord();

$params = [
    'INTERVENANT_ID' => 784094,
    'TYPE_VOLUME_HORAIRE_ID' => 1,
    'ETAT_VOLUME_HORAIRE_ID' => 1,
    //'STATUT_ID' => 744,
    //'ANNEE_ID'  => 2023,
];

$sTbl->calculer('formule', $params);