<?php

namespace Utilisateur\Service;

use Psr\Container\ContainerInterface;


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

        return $service;
    }
}