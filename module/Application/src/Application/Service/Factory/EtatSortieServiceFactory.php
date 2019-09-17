<?php

namespace Application\Service\Factory;

use Application\Constants;
use Interop\Container\ContainerInterface;
use Application\Service\EtatSortieService;



/**
 * Description of EtatSortieServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtatSortieServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtatSortieService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new EtatSortieService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}