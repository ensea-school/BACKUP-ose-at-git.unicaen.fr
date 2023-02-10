<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\FieldsetElementPedagogiqueRecherche;
use Psr\Container\ContainerInterface;


/**
 * Description of FieldsetElementPedagogiqueRechercheFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FieldsetElementPedagogiqueRechercheFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FieldsetElementPedagogiqueRecherche
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): FieldsetElementPedagogiqueRecherche
    {
        $viewHelper = new FieldsetElementPedagogiqueRecherche;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

