<?php
/**
 * Laminas Framework (http://framework.Laminas.com/)
 *
 * @link      http://github.com/Laminas/LaminasSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.Laminas.com)
 * @license   http://framework.Laminas.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\ORM\RouteEntitiesInjector;
use Laminas\Config\Factory as ConfigFactory;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Glob;
use UnicaenAuthentification\Service\UserContext;

include_once(__DIR__ . '/functions.php');


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // Récupération du container, ici le serviceManager de l'application
        $container = $e->getApplication()->getServiceManager();

        // Injection des entités à partir ID transmis dans les routes
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, $container->get(RouteEntitiesInjector::class), -90);

        // On force le rôle courant à NULL si on se déconnecte
        // Cela permet, si on a le choix de plusieurs rôles et qu'on en prend un qui n'est pas celui par défaut,
        // de se reconnecter avec le rôle par défaut et non le rôle précédemment sélectionné
        /** @var $userContext UserContext */
        $userContext = $container->get(UserContext::class);
        $adapter = $container->get('ZfcUser\Authentication\Adapter\AdapterChain');

        $adapter->getEventManager()->attach('logout', function ($e) use ($userContext) {
            $userContext->setSelectedIdentityRole(null);
        });
    }



    public function getConfig()
    {
        $paths = Glob::glob(dirname(__DIR__) . '/config/{,*.}{config}.php', Glob::GLOB_BRACE);

        return ConfigFactory::fromFiles($paths);
    }

}
