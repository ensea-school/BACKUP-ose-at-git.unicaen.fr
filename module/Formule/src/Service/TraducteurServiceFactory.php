<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TraducteurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TraducteurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TraducteurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TraducteurService
    {
        $service = new TraducteurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}