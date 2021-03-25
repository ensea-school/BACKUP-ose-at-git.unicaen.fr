<?php

namespace ExportRh\Connecteur\Siham;

use Psr\Container\ContainerInterface;

class SihamConnecteurFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return LdapConnecteur
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $config = $container->get('Config');

        $configSiham = [];
        if (isset($config['export-rh']['siham-ws'])) {
            $configSiham = $config['export-rh']['siham-ws'];
        }

        $service = new SihamConnecteur(
            $configSiham,
        );

        return $service;
    }

}