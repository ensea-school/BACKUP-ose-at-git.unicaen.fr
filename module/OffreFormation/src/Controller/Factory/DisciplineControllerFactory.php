<?php


namespace OffreFormation\Controller\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Controller\DisciplineController;


/**
 * Description of DisciplineControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DisciplineControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DisciplineController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DisciplineController
    {
        $controller = new DisciplineController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}

