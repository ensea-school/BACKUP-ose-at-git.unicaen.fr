<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */


$ws = $container->get(\Workflow\Service\WorkflowService::class);


$etapes = $ws->getEtapes();

foreach ($etapes as $etape) {
    echo str_pad($etape->getCode(), 33, ' ').' => '.$etape."\n";
    if ($etape->getContraintes()){
        foreach( $etape->getContraintes() as $contrainte){
            echo '    '.$contrainte->getDescNonFranchie()."\n";
        }
        echo "\n";
    }
}