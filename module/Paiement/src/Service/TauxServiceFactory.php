<?php

namespace Paiement\Service;

use Paiement\Entity\Db\TauxRemu;
use Psr\Container\ContainerInterface;


/**
 * Description of TauxServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxService
    {
        $service = new TauxService;

        /* Injectez vos dépendances ICI */
        return $service;
    }
}

