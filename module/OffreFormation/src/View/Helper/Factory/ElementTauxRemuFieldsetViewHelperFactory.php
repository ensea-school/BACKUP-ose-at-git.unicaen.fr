<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\ElementTauxRemuFieldsetViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of ElementTauxRemusFieldsetViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementTauxRemuFieldsetViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementTauxRemuFieldsetViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementTauxRemuFieldsetViewHelper
    {
        $viewHelper = new ElementTauxRemuFieldsetViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

