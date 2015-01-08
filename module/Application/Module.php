<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;

class Module implements ControllerPluginProviderInterface, ViewHelperProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $sm->get('translator');

        $this->injectJsFiles($sm);

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach($sm->get('AuthenticatedUserSavedListener'));
        
        /* Utilise un layout spécial si on est en AJAX. Valable pour TOUS les modules de l'application */
        $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
            function( \Zend\Mvc\MvcEvent $e) {
                if ($e->getRequest()->isXmlHttpRequest()){
                    $e->getTarget()->layout('application/ajax-layout.phtml');
                }
            }
        );
        
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'injectRouteEntitiesInEvent'), -90);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkRouteParams'), -100);
    }
    
    public function injectJsFiles(ServiceLocatorInterface $serviceLocator)
    {
        $basePath = dirname($_SERVER['PHP_SELF']);
        if ( substr($basePath, -1) !== '/' ) $basePath .= '/';
        $jsFiles = [
            'js/elementPedagogiqueRecherche.js'
        ];

        foreach( $jsFiles as $jsFile ){
            $serviceLocator->get('viewhelpermanager')->get('HeadScript')->appendFile($basePath.$jsFile);
        }
    }

    /**
     * Recherche de chaque entité spécifiée par son identifiant dans la requête courante,
     * et injection de cette entité dans l'événement MVC courant. 
     * 
     * @param \Zend\Mvc\MvcEvent $e
     * @see Service\NavigationPagesProvider
     */
    public function injectRouteEntitiesInEvent(MvcEvent $e)
    {
        $smPrefix = 'Application';
        $sm = $e->getApplication()->getServiceManager();
        $params = $e->getRouteMatch()->getParams();

        foreach( $params as $name => $value ){
            if ('intervenant' === $name){
                $value = $sm->get($smPrefix.ucfirst($name))->getBySourceCode( $value );
                $e->setParam($name, $value);
            }elseif ($sm->has($smPrefix.$name)){ // Si un service est associé à l'entité
                $service = $sm->get($smPrefix.ucfirst($name));
                if ($service instanceof Service\AbstractEntityService){
                    $value = $sm->get($smPrefix.ucfirst($name))->get( $value );
                    $e->setParam($name, $value);
                }
            }
        }
    }

    /**
     * Si l'utilisateur connecté a le profil "Intervenant", vérification que l'intervenant spécifié dans 
     * la requête est bien celui connecté.
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function checkRouteParams(MvcEvent $e)
    {
        $role = $e->getApplication()->getServiceManager()->get('ApplicationContextProvider')->getSelectedIdentityRole();
        $routeMatch = $e->getRouteMatch();
        if ($role instanceof Acl\IntervenantRole) {
            if (($value = $routeMatch->getParam($name = 'intervenant')) && $value != $role->getIntervenant()->getSourceCode()) {
                $routeMatch->setParam($name, $role->getIntervenant()->getSourceCode());
            }
            $routeMatch->setParam('intervenant', $role->getIntervenant()->getSourceCode());
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getControllerPluginConfig()
    {
        return array(
            'invokables' => array(
                'em'      => 'Application\Controller\Plugin\Em',
                'context' => 'Application\Controller\Plugin\Context',
            ),
            'factories' => array(
                'intervenant'        => 'Application\Controller\Plugin\IntervenantFactory',
                'serviceReferentiel' => 'Application\Controller\Plugin\ServiceReferentielFactory',
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     * @see ViewHelperProviderInterface
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories'  => array(
            ),
            'invokables' => array(
                'intervenantDl'        => 'Application\View\Helper\IntervenantDl',
                'adresseDl'            => 'Application\View\Helper\AdresseDl',
                'elementPedagogiqueDl' => 'Application\View\Helper\OffreFormation\ElementPedagogiqueDl',
                'etapeDl'              => 'Application\View\Helper\OffreFormation\EtapeDl',
                'fieldsetElementPedagogiqueRecherche' => 'Application\View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche',
            ),
        );
    }
    
    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return array(
            "Notifications",
            'notifier indicateurs [--force] --requestUriHost= [--requestUriScheme=]' => "Notification par mail des personnes abonnées à des indicateurs",
            array('--force', "Facultatif",  "Envoie les mails sytématiquement, sans tenir compte de la fréquence de notification."),
            array('--requestUriHost',   "Obligatoire", "Exemples: \"/ose.unicaen.fr\", \"/test.unicaen.fr/ose\"."),
            array('--requestUriScheme', "Facultatif",  "Exemples: \"http\" (par défaut), \"https\"."),
        );
    }
    
    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return "OSE Application Module";
    }
}


class Listener extends \BjyAuthorize\Guard\Controller
{
    
}