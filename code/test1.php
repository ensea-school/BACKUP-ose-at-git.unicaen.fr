<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */


$ws = $container->get(\Workflow\Service\WorkflowService::class);


$etapes = $ws->getEtapes();

foreach ($etapes as $etape) {
    echo $etape->getCode().' => '.$etape."\n";
}