<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\ElementModulateursSaisieFieldset;
use Psr\Container\ContainerInterface;


/**
 * Description of ElementModulateursSaisieFieldsetFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursSaisieFieldsetFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementModulateursSaisieFieldset
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementModulateursSaisieFieldset
    {
        $viewHelper = new ElementModulateursSaisieFieldset;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

