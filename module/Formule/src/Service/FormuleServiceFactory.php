<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\BddService;
use UnicaenTbl\Service\TableauBordService;


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

        $service->setServiceTableauBord($container->get(TableauBordService::class));

        return $service;
    }
}