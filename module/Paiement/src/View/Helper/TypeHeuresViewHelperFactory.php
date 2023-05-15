<?php

namespace Paiement\View\Helper;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeHeuresViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeHeuresViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeHeuresViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeHeuresViewHelper
    {
        $viewHelper = new TypeHeuresViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}