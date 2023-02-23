<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\EtapeTauxRemuFormViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of EtapeCentreCoutFormViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeTauxRemuFormViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeTauxRemuFormViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeTauxRemuFormViewHelper
    {
        $viewHelper = new EtapeTauxRemuFormViewHelper();

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

