<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

$ca = new ConnecteurPegase();
$ca->init();


$ddl = $ca->getDdl();
//// On ne touche pas à autre chose que la partie pegase!!
$filters = $ddl->filterOnlyDdl();
//// Mise à jour de la BDD (structures)
$bdd->alter($ddl, $filters);
//
$ca->read();
$ca->adapt();
$ca->extractOdf();


echo $ca->afficherArbre();


