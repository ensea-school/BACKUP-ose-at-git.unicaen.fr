<?php

namespace Chargens\Form;

use Psr\Container\ContainerInterface;

class DuplicationScenarioFormFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DuplicationScenarioForm
    {
        $form = new DuplicationScenarioForm();

        return $form;
    }
}