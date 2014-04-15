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

        $eventManager->attach(new AuthenticatedUserSavedListener($sm->get('doctrine.entitymanager.orm_default')));

//        $eventManager->attach(new ModalListener());
        
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
        
//        $eventManager->attach('render',
//             function($e) {
//                $modal = (bool) $e->getRequest()->getQuery('modal', $e->getRequest()->getPost('modal', 0));
//                var_dump($modal);
//                if (!$modal) {
//                    return;
//                }
//$ex = new \Exception;
//                $result = $e->getViewModel();
//                if (!$result instanceof \Zend\View\Model\ViewModel) {
//                    return;
//                }
//                
//                var_dump($result, $ex->getTraceAsString());
////                if (is_array($result)) {
////                    $result = new \Zend\View\Model\ViewModel($result);
////                }
////                elseif (empty($result)) {
////                    $result = new \Zend\View\Model\ViewModel();
////                }
//
//                $title         = "Test modale";
//                $displaySubmit = false;
//
//                if (!$e->getRequest()->isXmlHttpRequest()) {
//                    $f = new \UnicaenApp\Filter\ModalViewModel($title, $displaySubmit);
//                }
//                else {
//                    $f = new \UnicaenApp\Filter\ModalInnerViewModel($title, $displaySubmit);
//                }
//                $modalViewModel = $f->filter($result);
//
//                $e->setResult($modalViewModel);
//        });
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
