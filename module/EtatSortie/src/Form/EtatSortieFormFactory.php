<?php

namespace EtatSortie\Form;

use Psr\Container\ContainerInterface;
use Signature\Service\SignatureFlowService;
use UnicaenSignature\Service\SignatureConfigurationService;


/**
 * Description of EtatSortieFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtatSortieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtatSortieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new EtatSortieForm;
        $form->setSignatureConfigurationService($container->get(SignatureConfigurationService::class));
        $form->setServiceSignatureFlow($container->get(SignatureFlowService::class));

        return $form;
    }
}