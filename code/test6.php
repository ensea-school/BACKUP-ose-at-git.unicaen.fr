<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\ElementPedagogique;


/** @var ElementPedagogique $sep *
$sep = $sl->get('applicationElementPedagogique');

$ep = $sep->get(4500);
//$ep = $sep->get(7535);

$sep->forcerTauxMixite($ep, 0.5, 0.5,0);
*/



//var_dump($ep);



$fn = '/home/laurent/test/test.txt';
$data = 'salut';

mkdir( dirname($fn));
chmod( dirname($fn), 0777);
$r = file_put_contents($fn, $data);
chmod($fn, 0777);

var_dump(realpath($fn));