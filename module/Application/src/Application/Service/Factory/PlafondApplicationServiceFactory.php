<?php

namespace Application\Service\Factory;

use Application\Constants;
use Application\Service\ContextService;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use Application\Service\PlafondApplicationService;



/**
 * Description of PlafondApplicationServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondApplicationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondApplicationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new PlafondApplicationService;
        $service->setServiceLocator($container);
        $service->setEntityManager($container->get(Constants::BDD));
        $service->setServiceContext($container->get(ContextService::class));

        return $service;
    }
}