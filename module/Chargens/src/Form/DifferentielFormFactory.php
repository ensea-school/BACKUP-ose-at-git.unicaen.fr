<?php

namespace Chargens\Form;

use Psr\Container\ContainerInterface;

class DifferentielFormFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DifferentielForm
    {
        $form = new DifferentielForm();

        return $form;
    }
}