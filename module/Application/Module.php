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

        $eventManager->attach($sm->get('AuthenticatedUserSavedListener'));
        
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
}


class Listener extends \BjyAuthorize\Guard\Controller
{
    
}