<?php

namespace Application\Service\Factory;

use Application\Service\UtilisateurService;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;



/**
 * Description of UtilisateurServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class UtilisateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UtilisateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new UtilisateurService();
        $service->setUserService( $container->get('UnicaenAuth\Service\User') );

        return $service;
    }
}