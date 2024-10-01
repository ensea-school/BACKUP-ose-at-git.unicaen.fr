<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of BudgetServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class BudgetServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return BudgetService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): BudgetService
    {
        $service = new BudgetService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}