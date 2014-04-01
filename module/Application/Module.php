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

class Module implements ControllerPluginProviderInterface, ViewHelperProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $sm->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
//        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'registerModalStrategy'), 100);

        $eventManager->attach(new AuthenticatedUserSavedListener($sm->get('doctrine.entitymanager.orm_default')));

        /* Déclare la dernière vue transmise comme terminale si on est en AJAX */
        $sharedEvents = $eventManager->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
             function($e) {
                $result = $e->getResult();
                if(is_array($result)){
                    $result = new \Zend\View\Model\ViewModel($result);
                    $e->setResult($result);
                }elseif(empty($result)){
                    $result = new \Zend\View\Model\ViewModel();
                    $e->setResult($result);
                }
                if ($result instanceof \Zend\View\Model\ViewModel) {
                    $result->setTerminal($e->getRequest()->isXmlHttpRequest());
                }
        });
    }
    
    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function registerModalStrategy($e)
    {
//        $matches    = $e->getRouteMatch();
//        $controller = $matches->getParam('controller');
//        if (false === strpos($controller, __NAMESPACE__)) {
//            // not a controller from this module
//            return;
//        }
        if (!$e->getParam('modal', false)) {
            return;
        }

//        // Potentially, you could be even more selective at this point, and test
//        // for specific controller classes, and even specific actions or request
//        // methods.

        // Set the JSON strategy when controllers from this module are selected
        $app          = $e->getTarget();
        $locator      = $app->getServiceManager();
        $view         = $locator->get('Zend\View\View');
//        $jsonStrategy = $locator->get('ViewJsonStrategy');
        $modalStrategy = new View\Renderer\ModalStrategy();

        // Attach strategy, which is a listener aggregate, at high priority
        $view->getEventManager()->attach($modalStrategy, 100);
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
            ),
        );
    }

    /**
     *
     * @return array
     * @see ServiceProviderInterface
     */
    public function getServiceConfig()
    {
         return array(
            'invokables' => array(
                'ApplicationOffreFormation' => 'Application\\Service\\OffreFormation',
                'ApplicationIntervenant' => 'Application\\Service\\Intervenant',
            ),
        );
    }
}
