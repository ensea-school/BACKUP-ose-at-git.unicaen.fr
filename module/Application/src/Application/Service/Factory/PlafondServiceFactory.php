<?php

namespace Application\Service\Factory;

use Application\Constants;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use Application\Service\PlafondService;



/**
 * Description of PlafondServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new PlafondService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}