<?php

namespace Administration\Form;

use Psr\Container\ContainerInterface;
use UnicaenSignature\Service\SignatureConfigurationService;


/**
 * Description of EtatSortieFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ParametresFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ParametresForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new ParametresForm();
        $form->setSignatureConfigurationService($container->get(SignatureConfigurationService::class));

        return $form;
    }
}