<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$wp = $container->get(\Workflow\Tbl\Process\WorkflowProcess::class);

$wp->test();

