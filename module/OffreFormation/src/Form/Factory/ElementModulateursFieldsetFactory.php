<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\ElementModulateursFieldset;


/**
 * Description of ElementModulateursFieldsetFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementModulateursFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementModulateursFieldset
    {
        $form = new ElementModulateursFieldset;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

