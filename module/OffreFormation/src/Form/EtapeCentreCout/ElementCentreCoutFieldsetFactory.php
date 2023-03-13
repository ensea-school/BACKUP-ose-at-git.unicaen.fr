<?php

namespace OffreFormation\Form\EtapeCentreCout;

use Psr\Container\ContainerInterface;


/**
 * Description of ElementCentreCoutFieldsetFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementCentreCoutFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementCentreCoutFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementCentreCoutFieldset
    {
        $form = new ElementCentreCoutFieldset;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

