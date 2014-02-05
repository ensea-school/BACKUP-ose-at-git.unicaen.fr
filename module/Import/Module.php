<?php

namespace Import;

class Module
{

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
            'Intervenant',
        );
        $factories = array(
        );
        foreach( $services as $service ){
            $factories['importService'.$service] = function($sm) use ($service){
                $className = 'Import\\Model\\Service\\'.$service;
                return new $className;
            };
        }
        return array(
            'factories' => $factories,
        );
    }
}