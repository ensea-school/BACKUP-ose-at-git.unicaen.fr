<?php

namespace Formule\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of FormuleTestIntervenantServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class TestServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TestService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new TestService;
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}