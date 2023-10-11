<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Lieu\Service\StructureService $ss */
$ss = $container->get(\Lieu\Service\StructureService::class);


$root = $ss->get(114); // DRH
//$root = $ss->get(54); // Unicaen
//$root = null;

$s = [];
$s=$ss->getTree($root);


foreach($s as $str){
    showStructure($str);
}



function showStructure(\Lieu\Entity\Db\Structure $s){
    echo "<div style='padding-left:4em'>";
    echo "<div>".$s->getLibelleCourt()."</div>";

    foreach( $s->getStructures() as $sub){
        showStructure($sub);
    }
    echo "</div>";
}