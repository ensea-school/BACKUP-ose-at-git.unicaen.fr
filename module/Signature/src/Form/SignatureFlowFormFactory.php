<?php

namespace Signature\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of SignatureFlowFormFactory
 *
 */
class SignatureFlowFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SignatureFlowForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SignatureFlowForm
    {
        $form = new SignatureFlowForm();

        /* Injectez vos dépendances ICI */

        return $form;
    }
}