<?php

namespace ExportRh\Controller;

use ExportRh\Service\ExportRhService;
use Psr\Container\ContainerInterface;


/**
 * Description of ExportRhControllerFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class ExportRhControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ExportRhController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $exportServiceRh = $container->get(ExportRhService::class);
        $controller      = new ExportRhController($exportServiceRh);

        return $controller;
    }
}