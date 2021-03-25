<?php

namespace ExportRh\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of IntervenantServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ExportRhServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ExportRhService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new ExportRhService();

        return $service;
    }
}