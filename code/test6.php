<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$sw = $container->get(\Workflow\Service\WorkflowService::class);

// GARDIE Melanie 2024/2025
$intervenant = $container->get(\Intervenant\Service\IntervenantService::class)->get(1063992);

$fdr = $sw->getFeuilleDeRoute($intervenant);

$etapes = $fdr->getEtapes();

dd($etapes);