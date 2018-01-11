<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Processus\PlafondProcessus;
use Application\Service\ElementPedagogiqueService;
use Application\Service\IntervenantService;
use Application\Service\PlafondService;
use Application\Service\TypeVolumeHoraireService;

/** @var PlafondProcessus $pp */
$pp = $sl->get(PlafondProcessus::class);

var_dump($pp );