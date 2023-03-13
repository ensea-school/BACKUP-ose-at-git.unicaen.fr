<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\EtapeViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of EtapeViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeViewHelper
    {
        $viewHelper = new EtapeViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }

}