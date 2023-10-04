<?php

namespace Lieu\Form;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

class StructureSaisieFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new StructureSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}