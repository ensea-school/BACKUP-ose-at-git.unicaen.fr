<?php

namespace Contrat\Processus;

use Psr\Container\ContainerInterface;


/**
 * Description of ContratProcessusFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ContratProcessusFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratProcessus
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ContratProcessus
    {
        $service = new ContratProcessus;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

