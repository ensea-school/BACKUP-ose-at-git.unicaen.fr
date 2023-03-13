<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Psr\Container\ContainerInterface;


/**
 * Description of ElementTauxRemuFieldsetFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementTauxRemuFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementTauxRemuFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementTauxRemuFieldset
    {
        $form = new ElementTauxRemuFieldset();

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

