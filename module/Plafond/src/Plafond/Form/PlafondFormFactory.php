<?php

namespace Plafond\Form;

use Interop\Container\ContainerInterface;


/**
 * Description of PlafondFormFactory
 *
 * @author UnicaenCode
 */
class PlafondFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondForm
    {
        /* On quitte le FormElementManager */
        $container = $container->getServiceLocator();

        $form = new PlafondForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}