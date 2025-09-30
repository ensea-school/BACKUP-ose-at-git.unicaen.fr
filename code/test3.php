<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

$um = $container->get(\Framework\User\UserManager::class);
$c = null;

//$c = $um->getPrivileges();


$c = $um->getProfiles();


echo '<br /><br /><br /><br /><br /><br /><br />';
dump($c);