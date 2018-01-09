<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\ElementPedagogiqueService;
use Application\Service\IntervenantService;
use Application\Service\PlafondService;

$sl->get(ElementPedagogiqueService::class)->getEntityManager()->getFilters()->enable('historique')->init([
    \Application\Entity\Db\VolumeHoraireEns::class,
]);


/** @var PlafondService $ps */
$ps = $sl->get(PlafondService::class);


/** @var IntervenantService $is */
$is = $sl->get(IntervenantService::class);

$ps->controle($is->get(26823));
