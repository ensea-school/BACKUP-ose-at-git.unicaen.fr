<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

echo '<br /><br /><br /><br /><br /><br /><br />';

$um = $container->get(\Framework\User\UserManager::class);
dump($um->getCurrent());
dump($um->getCurrentProfile());

$intervenant = $um->getCurrentProfile()->getContext('intervenant');

dump($intervenant->getId());

$ldap = $container->get(\Utilisateur\Connecteur\LdapConnecteur::class);

dump($ldap->getUtilisateurCourantCode());




