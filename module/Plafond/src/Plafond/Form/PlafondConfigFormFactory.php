<?php

namespace Plafond\Form;

use Interop\Container\ContainerInterface;


/**
 * Description of PlafondConfigFormFactory
 *
 * @author UnicaenCode
 */
class PlafondConfigFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondConfigForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondConfigForm
    {
        $form = new PlafondConfigForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}