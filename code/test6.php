<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

use Formule\Model\FormuleDetailsExtractor;

$fs = $container->get(\Formule\Service\FormuleService::class);

$fi = $fs->getFormuleServiceIntervenant(783665, 1, 1);

$extractor = new FormuleDetailsExtractor();
$data      = $extractor->extract($fi);
