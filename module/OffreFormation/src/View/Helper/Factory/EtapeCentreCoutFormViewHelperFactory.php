<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\EtapeCentreCoutFormViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of EtapeCentreCoutFormViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeCentreCoutFormViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeCentreCoutFormViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeCentreCoutFormViewHelper
    {
        $viewHelper = new EtapeCentreCoutFormViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

