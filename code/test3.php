<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

echo '<br /><br /><br /><br /><br /><br /><br />';

$p = $container->get(\Framework\Authorize\RuleProvider::class);

$d = $p->getAll();

dump($d);

