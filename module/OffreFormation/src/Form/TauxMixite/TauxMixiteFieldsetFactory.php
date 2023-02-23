<?php

namespace OffreFormation\Form\TauxMixite;

use Psr\Container\ContainerInterface;


/**
 * Description of TauxMixiteFieldsetFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxMixiteFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxMixiteFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxMixiteFieldset
    {
        $form = new TauxMixiteFieldset;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

