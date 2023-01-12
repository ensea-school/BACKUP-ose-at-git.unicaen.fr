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
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Glob;
use Laminas\Config\Factory as ConfigFactory;

include_once(__DIR__ . '/src/functions.php');
include_once(__DIR__ . '/src/viteHelpers.php');





class Module
{
    private $modules = [];



    public function onBootstrap(MvcEvent $e)
    {
        $container = $e->getApplication()->getServiceManager();

        if (empty(\Application::$container)) {
            \Application::$container = $container;
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

        $eventManager->attach(MvcEvent::EVENT_ROUTE, $container->get(RouteEntitiesInjector::class), -90);

        /** @var $userContext \UnicaenAuth\Service\UserContext */
        $userContext = $container->get('UnicaenAuth\Service\UserContext');
        $adapter     = $container->get('ZfcUser\Authentication\Adapter\AdapterChain');

        $adapter->getEventManager()->attach('logout', function ($e) use ($userContext) {
            $userContext->setSelectedIdentityRole(null);
        });
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



    public function getConsoleUsage()
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



    public function getConsoleBanner()
    {
        return "OSE";
    }
}
