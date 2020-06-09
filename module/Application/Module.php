<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\ContextService;
use Psr\Container\ContainerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\Stdlib\Glob;
use Zend\Config\Factory as ConfigFactory;

include_once(__DIR__ . '/src/Application/functions.php');





class Module implements ConsoleUsageProviderInterface, ConsoleBannerProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        if (empty(\Application::$container)) {
            \Application::$container = $e->getApplication()->getServiceManager();
        }

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* Utilise un layout spécial si on est en AJAX. Valable pour TOUS les modules de l'application */
        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch',
            function (MvcEvent $e) {
                $request = $e->getRequest();
                if ($request instanceof \Zend\Http\Request && $request->isXmlHttpRequest()) {
                    $e->getTarget()->layout('layout/ajax.phtml');
                }
            }
        );

        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'injectRouteEntitiesInEvent'], -90);

        /** @var $userContext \UnicaenAuth\Service\UserContext */
        $userContext = \Application::$container->get('UnicaenAuth\Service\UserContext');
        $adapter     = \Application::$container->get('ZfcUser\Authentication\Adapter\AdapterChain');

        $adapter->getEventManager()->attach('logout', function ($e) use ($userContext) {
            $userContext->setSelectedIdentityRole(null);
        });
    }



    /**
     * Recherche de chaque entité spécifiée par son identifiant dans la requête courante,
     * et injection de cette entité dans l'événement MVC courant.
     *
     * @param \Zend\Mvc\MvcEvent $e
     *
     * @see Service\NavigationPagesProvider
     */
    public function injectRouteEntitiesInEvent(MvcEvent $e)
    {
        $sm     = $e->getApplication()->getServiceManager();
        $params = $e->getRouteMatch()->getParams();
        foreach ($params as $name => $value) {
            $entityService = $this->getEntityService($sm, $name);

            if ($entityService instanceof Service\AbstractEntityService) {
                switch ($name) {
                    case 'intervenant':
                        $role = $sm->get(ContextService::class)->getSelectedIdentityRole();
                        if ($role && $role->getIntervenant()) {
                            $e->setParam($name, $role->getIntervenant());
                        } else {
                            $entity = $entityService->getBySourceCode($value);
                            $e->setParam($name, $entity);
                        }
                    break;
                    case 'typeAgrementCode':
                        $entity = $entityService->getByCode($value);
                        $e->setParam('typeAgrement', $entity);
                    break;
                    default:
                        $entity = $entityService->get($value);
                        $e->setParam($name, $entity);
                }
            }
        }
    }



    private function getEntityService(ContainerInterface $container, $paramName)
    {
        if ('typeAgrementCode' === $paramName) {
            $paramName = 'typeAgrement';
        }

        $serviceName = 'Application\\Service\\' . ucfirst($paramName) . 'Service';
        if ($container->has($serviceName)) {
            return $container->get($serviceName);
        }

        return null;
    }



    public function getConfig()
    {
        $paths = Glob::glob(__DIR__ . '/config/{,*.}{config}.php', Glob::GLOB_BRACE);

        return ConfigFactory::fromFiles($paths);
    }



    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }



    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return [
            "Notifications",
            'notifier indicateurs [--force]' => "Notification par mail des personnes abonnées à des indicateurs",
            ['--force', "Facultatif", "Envoie les mails sytématiquement, sans tenir compte de la fréquence de notification."],
            "Charges d'enseignement",
            'chargens-calc-effectifs'        => "Calcul des effectifs du module Charges",
            "Tableaux de bord",
            'calcul-tableaux-bord'           => "Calcul de tous les tableaux de bord (sauf la formule qui est à part)",
            "Formule de calcul",
            'formule-calcul'                 => "Calcul de toutes les heures complémentaires à l'aide de la formule",

            "Administration : Changement de mot de passe",
            'changement-mot-de-passe'        => "Paramètres : --utilisateur, --mot-de-passe",
        ];
    }



    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return "OSE";
    }
}
