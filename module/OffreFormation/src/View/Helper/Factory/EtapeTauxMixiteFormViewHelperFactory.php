<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\EtapeTauxMixiteFormViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of EtapeTauxMixiteFormViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeTauxMixiteFormViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeTauxMixiteFormViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeTauxMixiteFormViewHelper
    {
        $viewHelper = new EtapeTauxMixiteFormViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

