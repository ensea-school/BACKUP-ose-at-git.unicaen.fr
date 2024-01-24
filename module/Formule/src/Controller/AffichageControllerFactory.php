<?php

namespace Formule\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of AffichageControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class AffichageControllerFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): AffichageController
    {

        $controller = new AffichageController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}