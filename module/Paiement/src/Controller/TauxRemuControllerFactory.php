<?php

namespace Paiement\Controller;

use Application\Service\ContextService;
use Paiement\Service\TauxRemuService;
use Psr\Container\ContainerInterface;

/**
 * Description of TauxRemuControllerFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxRemuControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxRemuController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxRemuController
    {
        $controller = new TauxRemuController;
        $controller->setServiceTauxRemu($container->get(TauxRemuService::class));
        $controller->setServiceContext($container->get(ContextService::class));

        return $controller;
    }
}

