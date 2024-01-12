<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of GradeServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class GradeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return GradeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new GradeService();

        return $service;
    }
}