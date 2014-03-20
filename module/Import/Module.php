<?php

namespace Import;

class Module
{

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        /* Déclare la dernière vue transmise comme terminale si on est en AJAX */
        $sharedEvents = $e->getApplication()->getEventManager()->getSharedManager();
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

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     *
     * @return array
     * @see ServiceProviderInterface
     */
    public function getServiceConfig()
    {
        $services = array(
            'Schema',
            'QueryGenerator',
            'Intervenant',
            'Differentiel',
        );
        $processus = array(
            'Import',
        );
        $factories = array();
        $invokables = array();
        foreach( $services as $service ){
            $factories['importService'.$service] = function($sm) use ($service){
                $className = 'Import\\Service\\'.$service;
                $so = new $className;
                if ($so instanceof \Common\Entity\UserAwareInterface){
                    $so->setCurrentUser( $sm->get('commonServiceUserContext')->getCurrentUser() );
                }
                return $so;
            };
        }
        foreach( $processus as $proc ){
            $invokables['importProcessus'.$proc] = 'Import\\Processus\\'.$proc;
        }
        return array(
            'factories' => $factories,
            'invokables' => $invokables,
        );
    }

    /**
     *
     * @return array
     * @see ViewHelperProviderInterface
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'differentielListe' => 'Import\View\Helper\DifferentielListe',
                'differentielLigne' => 'Import\View\Helper\DifferentielLigne\DifferentielLigne',
            ),
        );
    }
}