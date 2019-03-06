<?php

namespace Application\Controller\Factory;

use Application\Controller\IndicateurController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndicateurControllerFactory
{
    /**
     * Create controller
     *
     * @param ControllerManager $controllerManager
     *
     * @return IndicateurController
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $sl = $controllerManager->getServiceLocator();

        $httpRouter = $sl->get('HttpRouter');
        $renderer = $sl->get('view_manager')->getRenderer();
        $cliConfig = $this->getCliConfig($sl);

        $controller = new IndicateurController( $httpRouter, $renderer, $cliConfig);

        return $controller;
    }



    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return array
     */
    private function getCliConfig(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        return [
            'domain' => isset($config['cli_config']['domain']) ? $config['cli_config']['domain'] : null,
            'scheme' => isset($config['cli_config']['scheme']) ? $config['cli_config']['scheme'] : null,
        ];
    }

}