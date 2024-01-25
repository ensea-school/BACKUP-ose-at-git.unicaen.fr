<?php

namespace Formule\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of TestControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class TestControllerFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): TestController
    {

        $controller = new TestController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}