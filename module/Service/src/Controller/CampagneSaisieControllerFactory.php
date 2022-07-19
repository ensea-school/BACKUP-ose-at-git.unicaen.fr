<?php

namespace Service\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of CampagneSaisieControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CampagneSaisieControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CampagneSaisieController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CampagneSaisieController
    {
        $controller = new CampagneSaisieController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}