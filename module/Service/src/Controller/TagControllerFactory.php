<?php

namespace Service\Controller;

use Psr\Container\ContainerInterface;

/**
 * Description of TagControllerFactory
 *
 */
class TagControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TagController
     */
    public function __invoke (ContainerInterface $container, $requestedName, $options = null): TagController
    {
        $controller = new TagController();

        /*Injectez vos dépendances ici*/

        return $controller;
    }
}