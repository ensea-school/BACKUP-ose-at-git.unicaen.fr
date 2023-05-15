<?php

namespace Paiement\View\Helper;

use Psr\Container\ContainerInterface;



/**
 * Description of DemandeMiseEnPaiementViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DemandeMiseEnPaiementViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DemandeMiseEnPaiementViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DemandeMiseEnPaiementViewHelper
    {
        $viewHelper = new DemandeMiseEnPaiementViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}