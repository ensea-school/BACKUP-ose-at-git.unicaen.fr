<?php

namespace Plafond\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of PlafondApplicationServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondApplicationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondApplicationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new PlafondApplicationService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}