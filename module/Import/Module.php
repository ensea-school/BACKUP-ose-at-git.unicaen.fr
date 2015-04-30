<?php

namespace Import;

class Module
{

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
        $services = [
            'Schema',
            'QueryGenerator',
            'Intervenant',
            'Differentiel',
        ];
        $processus = [
            'Import',
        ];
        $factories = [];
        $invokables = [];
        foreach( $services as $service ){
            $factories['importService'.$service] = function($sm) use ($service){
                $className = 'Import\\Service\\'.$service;
                $so = new $className;
                return $so;
            };
        }
        foreach( $processus as $proc ){
            $invokables['importProcessus'.$proc] = 'Import\\Processus\\'.$proc;
        }
        return [
            'factories' => $factories,
            'invokables' => $invokables,
        ];
    }

    /**
     *
     * @return array
     * @see ViewHelperProviderInterface
     */
    public function getViewHelperConfig()
    {
        return [
            'invokables' => [
                'differentielListe' => 'Import\View\Helper\DifferentielListe',
                'differentielLigne' => 'Import\View\Helper\DifferentielLigne\DifferentielLigne',
            ],
        ];
    }
}