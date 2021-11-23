<?php
/**
 * Laminas Framework (http://framework.Laminas.com/)
 *
 * @link      http://github.com/Laminas/LaminasSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Laminas Technologies USA Inc. (http://www.Laminas.com)
 * @license   http://framework.Laminas.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\ContextService;
use Psr\Container\ContainerInterface;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Laminas\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Laminas\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Laminas\Stdlib\Glob;
use Laminas\Config\Factory as ConfigFactory;

include_once(__DIR__ . '/src/functions.php');





class Module implements ConsoleUsageProviderInterface, ConsoleBannerProviderInterface
{
    private $modules = [];



    public function onBootstrap(MvcEvent $e)
    {
        if (empty(\Application::$container)) {
            \Application::$container = $e->getApplication()->getServiceManager();
        }

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* Utilise un layout spécial si on est en AJAX. Valable pour TOUS les modules de l'application */
        $eventManager->getSharedManager()->attach('Laminas\Mvc\Controller\AbstractActionController', 'dispatch',
            function (MvcEvent $e) {
                $request = $e->getRequest();
                if ($request instanceof \Laminas\Http\Request && $request->isXmlHttpRequest()) {
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
     * @param \Laminas\Mvc\MvcEvent $e
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
                        /* @var $role \Application\Acl\Role */
                        $role   = $sm->get(ContextService::class)->getSelectedIdentityRole();
                        $entity = $entityService->getByRouteParam($value);
                        if ($role && $role->getIntervenant()) {
                            if ($role->getIntervenant()->getCode() != $entity->getCode()) {
                                $entity = $role->getIntervenant(); // c'est l'intervenant du rôle qui prime
                            } else {
                                $role->setIntervenant($entity); // Si c'est la même personne, on lui donne sa fiche d'ID demandée
                            }
                        }
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



    private function getEntityService(ContainerInterface $container, $paramName)
    {
        if (empty($this->modules)) {
            $moduleManager = $container->get('ModuleManager');
            /* @var $moduleManager ModuleManager */

            $this->modules = $moduleManager->getModules();
        }

        if ('typeAgrementCode' === $paramName) {
            $paramName = 'typeAgrement';
        }

        foreach ($this->modules as $module) {
            $serviceName = $module . '\\Service\\' . ucfirst($paramName) . 'Service';
            if ($container->has($serviceName)) {
                return $container->get($serviceName);
            }
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
            'Laminas\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src',
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
            "Administration : Recalcul de la complétude des dossiers",
            'calcul-completude-dossier'      => "Paramètres : --annee, --intervenant",
        ];
    }



    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return "OSE";
    }
}
