<?php

namespace Framework;

use Application\ConfigFactory;
use Framework\Authorize\Authorize;
use Framework\User\UserManager;
use Framework\User\UserAdapterInterface;
use Laminas\Mvc\MvcEvent;

class Module
{

    public function getConfig(): array
    {
        return ConfigFactory::configFromSimplified(dirname(__DIR__), __NAMESPACE__);
    }



    public function onBootstrap(MvcEvent $e)
    {
        // Récupération du container, ici le serviceManager de l'application
        $container = $e->getApplication()->getServiceManager();

        // Créer une factory pour ça...
        $userProviderClass = $container->get('config')['unicaen-framework']['user_provider'] ?? null;
        if (!$userProviderClass) {
            throw new \Exception('User provider not configured in unicaen-framework/user_provider');
        }

        $userProvider = $container->get($userProviderClass);
        if (!$userProvider instanceof UserAdapterInterface) {
            throw new \Exception('User provider must be a '.UserAdapterInterface::class);
        }

        $userManager = $container->get(UserManager::class);
        $userManager->setUserAdapter($userProvider);
        $userManager->setUser($userProvider->getUser());

        /* Déclenchement du service Authorize */
        $authorize = $container->get(Authorize::class);
        $authorize->attach($e->getTarget()->getEventManager());
    }
}