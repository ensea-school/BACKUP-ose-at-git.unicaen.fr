<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\ElementPedagogiqueRechercheFieldset;


/**
 * Description of ElementPedagogiqueRechercheFieldsetFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueRechercheFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementPedagogiqueRechercheFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementPedagogiqueRechercheFieldset
    {
        $form = new ElementPedagogiqueRechercheFieldset;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

