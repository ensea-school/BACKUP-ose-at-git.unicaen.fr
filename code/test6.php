<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

use Formule\Model\FormuleDetailsExtractor;

$fs = $container->get(\Formule\Service\FormuleService::class);


//2023/2024 - DALMASSO Marion
$fi = $fs->getFormuleServiceIntervenant(783665, 1, 1);

if (null == $fi->getArrondisseurTrace()) {
    $fs->calculer($fi);
}
$trace = $fi->getArrondisseurTrace();

$vu = $trace->getValeursUtilisees();

var_dump($vu);