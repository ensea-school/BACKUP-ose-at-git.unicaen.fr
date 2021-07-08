<?php

namespace ExportRh\Service;

use ExportRh\Connecteur\Siham\SihamConnecteur;
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
        $config = $container->get('Config');

        switch ($config['export-rh']['connecteur']) {
            default:
                $connecteur = $container->get(SihamConnecteur::class);
            break;
        }

        $service = new ExportRhService($connecteur);

        return $service;
    }
}