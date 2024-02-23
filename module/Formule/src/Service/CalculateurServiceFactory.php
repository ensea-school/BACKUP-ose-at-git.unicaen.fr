<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of CalculateurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CalculateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CalculateurService
    {
        $service = new CalculateurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}