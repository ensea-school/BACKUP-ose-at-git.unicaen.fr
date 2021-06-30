<?php

namespace ExportRh\Connecteur\Siham;

use Psr\Container\ContainerInterface;
use UnicaenSiham\Service\Siham;

class SihamConnecteurFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SihamConnecteur
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $siham           = $container->get(Siham::class);
        $sihamConnecteur = new SihamConnecteur($siham);

        return $sihamConnecteur;
    }

}