<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use Unicaen\BddAdmin\Bdd;

require_once dirname(__DIR__) . '/admin/src/OseAdmin.php';
require_once dirname(__DIR__) . '/admin/pegase/src/ConnecteurPegase.php';

$oa = OseAdmin::instance();
$ca = new ConnecteurPegase();
$ca->init();


$ddl = $ca->getDdl();
//// On ne touche pas à autre chose que la partie pegase!!
$filters = $ddl->filterOnlyDdl();
//// Mise à jour de la BDD (structures)
$oa->getBdd()->alter($ddl, $filters);
//
$ca->read();
$ca->adapt();
$ca->extractOdf();


echo $ca->afficherArbre();


