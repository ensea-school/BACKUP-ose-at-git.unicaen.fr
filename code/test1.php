<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */


$ws = $container->get(\Workflow\Service\WorkflowService::class);


$etapes = $ws->getEtapes();

foreach ($etapes as $etape) {
    echo str_pad($etape->getCode(), 33, ' ').' => '.$etape."\n";
    if ($etape->getDependances()){
        foreach( $etape->getDependances() as $dep){
            echo '    '.$dep->getEtapePrecedante().' '.$dep->getPerimetre().' '.$dep->getAvancement()."\n";
        }
        echo "\n";
    }
}