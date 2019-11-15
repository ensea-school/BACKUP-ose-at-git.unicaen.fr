<?php

namespace Application\Service\Factory;

use Application\Constants;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use Application\Service\ModeleContratService;



/**
 * Description of ModeleContratServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ModeleContratServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ModeleContratService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new ModeleContratService;
        $service->setEntityManager($container->get(Constants::BDD));

        $config = $container->get('Config');

        if (isset($config['application']['etats-sortie'])){
            $service->setConfig($config['application']['etats-sortie']);
        }

        return $service;
    }
}