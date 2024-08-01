<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of SituationMatrimonialeServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SituationMatrimonialeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CiviliteService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new SituationMatrimonialeService();

        return $service;
    }
}