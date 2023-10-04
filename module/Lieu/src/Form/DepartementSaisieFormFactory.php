<?php

namespace Lieu\Form;

use Psr\Container\ContainerInterface;

class DepartementSaisieFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new DepartementSaisieForm();

        return $form;
    }
}