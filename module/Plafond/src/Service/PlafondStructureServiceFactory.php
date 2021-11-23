<?php

namespace Plafond\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of PlafondStructureServiceFactory
 *
 * @author UnicaenCode
 */
class PlafondStructureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondStructureService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondStructureService
    {
        $service = new PlafondStructureService;
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}