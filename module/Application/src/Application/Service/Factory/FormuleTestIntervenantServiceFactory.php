<?php

namespace Application\Service\Factory;

use Application\Constants;
use Psr\Container\ContainerInterface;
use Application\Service\FormuleTestIntervenantService;


/**
 * Description of FormuleTestIntervenantServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FormuleTestIntervenantServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FormuleTestIntervenantService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new FormuleTestIntervenantService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}