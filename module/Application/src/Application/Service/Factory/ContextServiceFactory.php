<?php

namespace Application\Service\Factory;

use Application\Service\ContextService;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;



/**
 * Description of IntervenantServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ContextServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContextService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new ContextService();
        $service->setServiceUserContext($container->get('UnicaenAuth\Service\UserContext'));

        return $service;
    }
}