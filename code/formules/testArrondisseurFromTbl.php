<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);


$sTbl = $fs->getServiceTableauBord();
$params = [
    'INTERVENANT_ID' => 899413,
    'TYPE_VOLUME_HORAIRE_ID' => 1,
    'ETAT_VOLUME_HORAIRE_ID' => 1,
    'STATUT_ID' => 744,
    'ANNEE_ID'  => 2023,
];
$sTbl->calculer(\Application\Provider\Tbl\TblProvider::FORMULE, $params);