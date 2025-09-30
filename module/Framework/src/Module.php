<?php

namespace Framework;

use Application\ConfigFactory;
use Framework\User\UserManager;
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

        $userManager = $container->get(UserManager::class);
        $userManager->detectChanges();
    }
}