<?php

namespace Plafond\Form;

use Interop\Container\ContainerInterface;


/**
 * Description of PlafondStructureFormFactory
 *
 * @author UnicaenCode
 */
class PlafondStructureFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondStructureForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondStructureForm
    {
        $form = new PlafondStructureForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}