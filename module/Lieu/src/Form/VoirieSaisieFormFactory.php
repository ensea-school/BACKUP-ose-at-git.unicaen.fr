<?php

namespace Lieu\Form;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

class VoirieSaisieFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null): VoirieSaisieForm
    {
        $form = new VoirieSaisieForm();

        return $form;
    }
}