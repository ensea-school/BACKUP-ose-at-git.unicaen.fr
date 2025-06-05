<?php

namespace Chargens\Form;

use Psr\Container\ContainerInterface;

class ScenarioFiltreFormFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ScenarioFiltreForm
    {
        $form = new ScenarioFiltreForm();

        return $form;
    }
}