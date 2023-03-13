<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\EtapeModulateursSaisieForm;
use Psr\Container\ContainerInterface;


/**
 * Description of EtapeModulateursSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeModulateursSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeModulateursSaisieForm
    {
        $viewHelper = new EtapeModulateursSaisieForm;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}

