<?php

namespace Application\Controller\Factory;

use Application\Form\Plafond\PlafondApplicationForm;
use Application\Service\PlafondApplicationService;
use Zend\Mvc\Controller\ControllerManager as ContainerInterface;
use Application\Controller\PlafondController;



/**
 * Description of PlafondControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $container = $container->getServiceLocator();

        $controller = new PlafondController;

        return $controller;
    }
}