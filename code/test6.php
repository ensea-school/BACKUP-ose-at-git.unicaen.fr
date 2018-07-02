<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Entity\VolumeHoraireListe;
use Application\Hydrator\VolumeHoraire\ListeFilterHydrator;
use Application\Service\ServiceService;

/** @var ServiceService $ss */
$ss = $sl->get(ServiceService::class);

$service = $ss->get(24519);

$vhl = new VolumeHoraireListe($service);

$sl = $vhl->getSousListes([
    $vhl::FILTRE_HORAIRE_DEBUT,
    $vhl::FILTRE_HORAIRE_FIN,
    $vhl::FILTRE_MOTIF_NON_PAIEMENT
]);

$h = new ListeFilterHydrator();

foreach( $sl as $vhl ){
    $d = $h->extractInts($vhl);

    var_dump($d);
}