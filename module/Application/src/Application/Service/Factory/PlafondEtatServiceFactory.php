<?php

namespace Application\Service\Factory;

use Application\Constants;
use Application\Service\ContextService;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use Application\Service\PlafondEtatService;



/**
 * Description of PlafondEtatServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondEtatServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondEtatService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new PlafondEtatService;
        $service->setServiceLocator($container);
        $service->setEntityManager($container->get(Constants::BDD));
        $service->setServiceContext($container->get(ContextService::class));

        return $service;
    }
}