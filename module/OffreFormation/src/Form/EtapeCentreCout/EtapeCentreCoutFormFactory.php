<?php

namespace OffreFormation\Form\EtapeCentreCout;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\EtapeCentreCout\EtapeCentreCoutForm;


/**
 * Description of EtapeCentreCoutFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeCentreCoutFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeCentreCoutForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeCentreCoutForm
    {
        $form = new EtapeCentreCoutForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

