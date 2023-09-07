<?php

namespace Mission\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of PrimeControllerFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PrimeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SuiviController
     */
    public function __invoke (ContainerInterface $container, $requestedName, $options = null): PrimeController
    {
        $controller = new PrimeController();

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}