<?php

namespace Application\Processus\Factory;

use Psr\Container\ContainerInterface;
use Application\Processus\PlafondProcessus;


/**
 * Description of PlafondProcessusFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondProcessusFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondProcessus
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $flashMessenger = $container->get('ControllerPluginManager')->get('FlashMessenger');

        $service = new PlafondProcessus($flashMessenger);

        return $service;
    }
}