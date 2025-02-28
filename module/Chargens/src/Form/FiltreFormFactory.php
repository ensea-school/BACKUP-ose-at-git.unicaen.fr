<?php

namespace Chargens\Form;

use Psr\Container\ContainerInterface;

class FiltreFormFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): FiltreForm
    {
        $form = new FiltreForm();

        return $form;
    }
}