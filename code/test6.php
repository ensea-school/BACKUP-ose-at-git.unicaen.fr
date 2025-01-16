<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$em = $container->get(\Doctrine\ORM\EntityManager::class);

$fts = $container->get(\Formule\Service\TestService::class);




//2023/2024 - DALMASSO Marion
$intervenantId = 783665;
$typeVolumeHoraireId = 1;
$etatVolumeHoraireId = 1;


$intervenant = $em->find(\Intervenant\Entity\Db\Intervenant::class, $intervenantId);
$typeVolumeHoraire = $em->find(\Service\Entity\Db\TypeVolumeHoraire::class, $typeVolumeHoraireId);
$etatVolumeHoraire = $em->find(\Service\Entity\Db\EtatVolumeHoraire::class, $etatVolumeHoraireId);


$ft = $fts->creerDepuisIntervenant($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

var_dump($ft);