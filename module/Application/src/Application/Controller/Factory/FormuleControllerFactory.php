<?php

namespace Application\Controller\Factory;

use Application\Controller\FormuleController;
use Psr\Container\ContainerInterface;


/**
 * Description of FormuleControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class FormuleControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FormuleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {

        $controller = new FormuleController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}