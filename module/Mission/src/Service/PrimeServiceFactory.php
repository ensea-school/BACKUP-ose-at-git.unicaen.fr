<?php

namespace Mission\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of PrimeServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PrimeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PrimeService
     */
    public function __invoke (ContainerInterface $container, $requestedName, $options = null): PrimeService
    {
        $service = new PrimeService;
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}