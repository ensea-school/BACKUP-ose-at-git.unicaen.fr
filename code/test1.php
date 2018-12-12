<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\EtatVolumeHoraireService;
use Application\Service\FormuleResultatService;
use Application\Service\IntervenantService;
use Application\Service\TypeVolumeHoraireService;

$intervenant = $sl->get(IntervenantService::class)->get(648);
$typeVolumeHoraire = $sl->get(TypeVolumeHoraireService::class)->get(1);
$etatVolumeHoraire = $sl->get(EtatVolumeHoraireService::class)->get(1);
$frs = $sl->get(FormuleResultatService::class);


$data = $frs->getData($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);

unset($data['structure-affectation']);


var_dump($data );