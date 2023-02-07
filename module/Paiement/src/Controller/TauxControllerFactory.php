<?php

namespace Paiement\Controller;

use Application\Service\ContextService;
use Paiement\Service\TauxService;
use Psr\Container\ContainerInterface;

/**
 * Description of TauxControllerFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxController
    {
        $controller = new TauxController;
        $controller->setServiceTaux($container->get(TauxService::class));
        $controller->setServiceContext($container->get(ContextService::class));

        return $controller;
    }
}

