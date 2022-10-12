<?php

namespace Service\View\Helper;

use Psr\Container\ContainerInterface;



/**
 * Description of HorodatageViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class HorodatageViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return HorodatageViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): HorodatageViewHelper
    {
        $viewHelper = new HorodatageViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}