<?php

namespace EtatSortie\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of EtatSortieServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtatSortieServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtatSortieService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new EtatSortieService;
        $service->setEntityManager($container->get(Constants::BDD));

        $config = $container->get('config');

        if (isset($config['application']['etats-sortie'])) {
            $service->setConfig($config['application']['etats-sortie']);
        }

        return $service;
    }
}