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

$em = $fs->getEntityManager();

$intervenant = $em->find(\Intervenant\Entity\Db\Intervenant::class, 778887);
$typeVolumeHoraire = $container->get(\Service\Service\TypeVolumeHoraireService::class)->getPrevu();
$etatVolumeHoraire = $container->get(\Service\Service\EtatVolumeHoraireService::class)->getSaisi();

$intervenantTest = $em->find(\Formule\Entity\Db\FormuleTestIntervenant::class, 10030);

//$res = $fs->getResultat($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);


$res = $fs->getTest($intervenantTest);


var_dump($res);