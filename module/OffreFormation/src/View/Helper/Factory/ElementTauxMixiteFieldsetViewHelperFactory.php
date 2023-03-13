<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\ElementTauxMixiteFieldsetViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of ElementTauxMixiteFieldsetViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementTauxMixiteFieldsetViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementTauxMixiteFieldsetViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementTauxMixiteFieldsetViewHelper
    {
        $viewHelper = new ElementTauxMixiteFieldsetViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

