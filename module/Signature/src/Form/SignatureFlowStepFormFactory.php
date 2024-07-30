<?php

namespace Signature\Form;

use Psr\Container\ContainerInterface;
use UnicaenSignature\Service\SignatureConfigurationService;


/**
 * Description of SignatureFlowStepFormFactory
 *
 */
class SignatureFlowStepFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SignatureFlowStepForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SignatureFlowStepForm
    {
        $form = new SignatureFlowStepForm();
        $form->setSignatureConfigurationService($container->get(SignatureConfigurationService::class));

        /* Injectez vos dépendances ICI */

        return $form;
    }
}