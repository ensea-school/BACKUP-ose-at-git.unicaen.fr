<?php

namespace Application\Service\Factory;

use Application\Service\ContextService;
use Framework\User\UserManager;
use Psr\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;


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
        $service = new ContextService(
            $container->get(UserManager::class),
        );
        $service->setServiceUserContext($container->get(UserContext::class));

        return $service;
    }
}