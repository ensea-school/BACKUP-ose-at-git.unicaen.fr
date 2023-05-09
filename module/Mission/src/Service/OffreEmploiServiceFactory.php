<?php

namespace Mission\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of OffreEmploiServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return OffreEmploiService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): OffreEmploiService
    {
        $service = new OffreEmploiService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}