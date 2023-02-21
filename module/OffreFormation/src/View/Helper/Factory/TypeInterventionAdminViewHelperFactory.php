<?php

namespace OffreFormation\View\Helper\Factory;

use OffreFormation\View\Helper\TypeInterventionAdminViewHelper;
use Psr\Container\ContainerInterface;


/**
 * Description of TypeInterventionAdminViewHelperFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionAdminViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionAdminViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionAdminViewHelper
    {
        $viewHelper = new TypeInterventionAdminViewHelper();

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }

}