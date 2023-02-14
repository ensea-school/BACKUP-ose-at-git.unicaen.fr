<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Psr\Container\ContainerInterface;


/**
 * Description of EtapeTauxRemuFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeTauxRemuFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeTauxRemuForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeTauxRemuForm
    {
        $form = new EtapeTauxRemuForm();

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

