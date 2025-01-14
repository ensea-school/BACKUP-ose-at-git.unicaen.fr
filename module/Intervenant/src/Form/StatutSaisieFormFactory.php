<?php

namespace Intervenant\Form;

use Psr\Container\ContainerInterface;
use Signature\Service\SignatureFlowService;
use UnicaenSignature\Service\SignatureConfigurationService;


/**
 * Description of StatutSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class StatutSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return StatutSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): StatutSaisieForm
    {
        $form = new StatutSaisieForm();

        return $form;
    }
}