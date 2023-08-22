<?php

namespace Plafond\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;


/**
 * Description of PlafondServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new PlafondService;
        $service->setEntityManager($container->get(Constants::BDD));
        $service->setServiceTableauBord($container->get(TableauBordService::class));

        return $service;
    }
}