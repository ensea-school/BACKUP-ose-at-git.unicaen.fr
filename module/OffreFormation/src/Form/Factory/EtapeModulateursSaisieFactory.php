<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\EtapeModulateursSaisie;


/**
 * Description of EtapeModulateursSaisieFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursSaisieFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeModulateursSaisie
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeModulateursSaisie
    {
        $form = new EtapeModulateursSaisie;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

