<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of CreateurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CreateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CreateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CreateurService
    {
        $service = new CreateurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}