<?php

namespace Application\View\Helper;

use Psr\Container\ContainerInterface;



/**
 * Description of TabViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TabViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TabViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TabViewHelper
    {
        $viewHelper = new TabViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}