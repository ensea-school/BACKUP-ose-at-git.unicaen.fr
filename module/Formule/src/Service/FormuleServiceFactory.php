<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\BddService;


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

        $service->setServiceBdd($container->get(BddService::class));

        return $service;
    }
}