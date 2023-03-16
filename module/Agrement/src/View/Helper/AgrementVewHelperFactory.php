<?php

namespace Agrement\View\Helper;

use Psr\Container\ContainerInterface;


/**
 * Description of AgrementVewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AgrementVewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AgrementViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AgrementViewHelper
    {
        $viewHelper = new AgrementViewHelper();

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}