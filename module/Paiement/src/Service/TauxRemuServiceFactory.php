<?php

namespace Paiement\Service;

use Paiement\Entity\Db\TauxRemu;
use Psr\Container\ContainerInterface;


/**
 * Description of TauxServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxRemuServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxRemuService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxRemuService
    {
        $service = new TauxRemuService;

        /* Injectez vos dépendances ICI */
        return $service;
    }
}

