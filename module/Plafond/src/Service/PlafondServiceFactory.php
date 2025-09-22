<?php

namespace Plafond\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
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
        $service->setEntityManager($container->get(EntityManager::class));
        $service->setServiceTableauBord($container->get(TableauBordService::class));
        $service->setBdd($container->get(Bdd::class));

        return $service;
    }
}