<?php

namespace Application\Service\Factory;

use Application\Constants;
use Interop\Container\ContainerInterface;
use Application\Service\FormuleService;



/**
 * Description of FormuleServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
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
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new FormuleService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}