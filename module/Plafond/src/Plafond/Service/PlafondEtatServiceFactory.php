<?php

namespace Plafond\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


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
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}