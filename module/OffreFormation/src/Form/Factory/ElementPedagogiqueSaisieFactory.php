<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\ElementPedagogiqueSaisie;


/**
 * Description of ElementPedagogiqueSaisieFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueSaisieFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementPedagogiqueSaisie
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementPedagogiqueSaisie
    {
        $form = new ElementPedagogiqueSaisie;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

