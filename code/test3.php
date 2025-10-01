<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

echo '<br /><br /><br /><br /><br /><br /><br />';

$um = $container->get(\Framework\User\UserManager::class);
$um->updatePrivileges();
dump($um->getPrivileges());




$a = $container->get(\Laminas\Authentication\AuthenticationService::class);
$c = $a->getIdentity();

dump($c);