<?php

namespace Application\Service\Factory;

use Application\Constants;
use Application\Service\TblBddService;
use Psr\Container\ContainerInterface;


/**
 * Remplace le BddService de unicaen/tbl (cf. TblBddService)
 */
class TblBddServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TblBddService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new TblBddService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}
