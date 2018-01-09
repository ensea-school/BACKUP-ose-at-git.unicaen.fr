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
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\Stdlib\Glob;
use Zend\Config\Factory as ConfigFactory;

include_once(__DIR__ . '/src/Application/functions.php');





class Module implements ConsoleUsageProviderInterface, ConsoleBannerProviderInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    public static $serviceLocator;



    public function onBootstrap(MvcEvent $e)
    {
        $sm                   = $e->getApplication()->getServiceManager();
        self::$serviceLocator = $sm; // Initialisation pour les filtres Doctrine et les traits!!

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* Utilise un layout spécial si on est en AJAX. Valable pour TOUS les modules de l'application */
        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch',
            function (\Zend\Mvc\MvcEvent $e) {
                if ($e->getRequest()->isXmlHttpRequest()) {
                    $e->getTarget()->layout('application/ajax-layout.phtml');
                }
            }
        );

        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'injectRouteEntitiesInEvent'], -90);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkRouteParams'], -100);
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
                        $entity = $entityService->getBySourceCode($value);
                        $e->setParam($name, $entity);
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



    private function getEntityService(ServiceLocatorInterface $serviceLocator, $paramName)
    {
        if ('typeAgrementCode' === $paramName) {
            $paramName = 'typeAgrement';
        }

        $serviceName = 'Application\\Service\\' . ucfirst($paramName) . 'Service';
        if ($serviceLocator->has($serviceName)) {
            return $serviceLocator->get($serviceName);
        }

        return null;
    }



    /**
     * Si l'utilisateur connecté a le profil "Intervenant", vérification que l'intervenant spécifié dans
     * la requête est bien celui connecté.
     *
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function checkRouteParams(MvcEvent $e)
    {
        $role       = $e->getApplication()->getServiceManager()->get(ContextService::class)->getSelectedIdentityRole();
        $routeMatch = $e->getRouteMatch();
        if ($role && $intervenant = $role->getIntervenant()) {
            if (($value = $routeMatch->getParam($name = 'intervenant')) && $value != $intervenant->getRouteParam()) {
                $routeMatch->setParam($name, $intervenant->getRouteParam());
            }
            $routeMatch->setParam('intervenant', $intervenant->getRouteParam());
        }
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
            ['--requestUriHost', "Obligatoire", "Exemples: \"/ose.unicaen.fr\", \"/test.unicaen.fr/ose\"."],
            ['--requestUriScheme', "Facultatif", "Exemples: \"http\" (par défaut), \"https\"."],
        ];
    }



    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return "OSE";
    }
}
