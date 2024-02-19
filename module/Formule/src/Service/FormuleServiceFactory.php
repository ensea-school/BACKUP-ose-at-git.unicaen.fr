<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of FormuleServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FormuleService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): FormuleService
    {
        $service = new FormuleService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}