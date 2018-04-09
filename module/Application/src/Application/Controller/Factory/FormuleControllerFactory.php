<?php

namespace Application\Controller\Factory;

use Zend\Mvc\Controller\ControllerManager as ContainerInterface;
use Application\Controller\FormuleController;



/**
 * Description of FormuleControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FormuleControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FormuleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        /* On quitte le ControllerManager */
        $container = $container->getServiceLocator();

        $controller = new FormuleController;
        /* Injectez vos dépendances ICI */

        return $controller;
    }
}