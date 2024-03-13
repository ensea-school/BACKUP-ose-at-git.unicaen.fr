<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of FormulatorServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormulatorServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FormulatorService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): FormulatorService
    {
        $service = new FormulatorService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}