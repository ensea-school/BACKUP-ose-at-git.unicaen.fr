<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\ElementCentreCoutFieldsetViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of ElementCentreCoutFieldsetViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementCentreCoutFieldsetViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementCentreCoutFieldsetViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementCentreCoutFieldsetViewHelper
    {
        $viewHelper = new ElementCentreCoutFieldsetViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

