<?php

namespace Plafond\View\Helper;

use Psr\Container\ContainerInterface;


/**
 * Description of PlafondsViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class PlafondsViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondsViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondsViewHelper
    {
        $viewHelper = new PlafondsViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}