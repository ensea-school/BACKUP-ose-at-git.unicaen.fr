<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of MiseEnPaiementIntervenantStructureServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementIntervenantStructureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MiseEnPaiementIntervenantStructureService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MiseEnPaiementIntervenantStructureService
    {
        $service = new MiseEnPaiementIntervenantStructureService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}