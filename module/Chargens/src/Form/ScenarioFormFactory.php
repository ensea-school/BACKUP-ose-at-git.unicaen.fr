<?php

namespace Chargens\Form;

use Psr\Container\ContainerInterface;

class ScenarioFormFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ScenarioForm
    {
        $form = new ScenarioForm();

        return $form;
    }
}