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
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\Glob;
use Laminas\Config\Factory as ConfigFactory;
use UnicaenAuthentification\Service\UserContext;

include_once(__DIR__ . '/src/functions.php');


class Module
{
    private $modules = [];



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
            'chargens-calc-effectifs' => "Calcul des effectifs du module Charges",
            "Tableaux de bord",
            'calcul-tableaux-bord' => "Calcul de tous les tableaux de bord (sauf la formule qui est à part)",
            "Formule de calcul",
            'formule-calcul' => "Calcul de toutes les heures complémentaires à l'aide de la formule",

            "Administration : Changement de mot de passe",
            'changement-mot-de-passe' => "Paramètres : --utilisateur, --mot-de-passe",
            "Administration : Recalcul de la complétude des dossiers",
            'calcul-completude-dossier' => "Paramètres : --annee, --intervenant",
        ];
    }



    public function getConsoleBanner()
    {
        return "OSE";
    }
}
