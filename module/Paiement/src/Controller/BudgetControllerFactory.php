<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of BudgetControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class BudgetControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return BudgetController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): BudgetController
    {
        $controller = new BudgetController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}