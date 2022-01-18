<?php

namespace Plafond\View\Helper;

use Psr\Container\ContainerInterface;



/**
 * Description of PlafondConfigElementViewHelperFactory
 *
 * @author UnicaenCode
 */
class PlafondConfigElementViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondConfigElementViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondConfigElementViewHelper
    {
        $viewHelper = new PlafondConfigElementViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}