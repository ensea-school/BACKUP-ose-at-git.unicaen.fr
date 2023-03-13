<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\ElementPedagogiqueSynchronisationForm;


/**
 * Description of ElementPedagogiqueSynchronisationFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueSynchronisationFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementPedagogiqueSynchronisationForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementPedagogiqueSynchronisationForm
    {
        $form = new ElementPedagogiqueSynchronisationForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

