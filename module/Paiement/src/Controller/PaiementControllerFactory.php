<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;


/**
 * Description of PaiementControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class PaiementControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PaiementController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PaiementController
    {
        $controller = new PaiementController;

        $controller->setServiceTableauBord( $container->get(TableauBordService::class));

        return $controller;
    }
}