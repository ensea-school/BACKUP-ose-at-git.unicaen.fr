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



class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // Récupération du container, ici le serviceManager de l'application
        $container = $e->getApplication()->getServiceManager();

        // Injection des entités à partir ID transmis dans les routes
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, $container->get(RouteEntitiesInjector::class), -90);
    }



    public function getConfig()
    {
        $paths = Glob::glob(dirname(__DIR__) . '/config/{,*.}{config}.php', Glob::GLOB_BRACE);

        return ConfigFactory::fromFiles($paths);
    }

}
