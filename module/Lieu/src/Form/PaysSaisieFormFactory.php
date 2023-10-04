<?php

namespace Lieu\Form;

use Psr\Container\ContainerInterface;

class PaysSaisieFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new PaysSaisieForm();

        return $form;
    }
}