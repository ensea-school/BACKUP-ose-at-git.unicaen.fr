<?php

namespace Indicateur\Service;

use Psr\Container\ContainerInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IndicateurServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new IndicateurService();

        return $service;
    }

}