<?php

namespace Lieu\Form;

use Psr\Container\ContainerInterface;

class EtablissementSaisieFormFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new EtablissementSaisieForm();

        return $form;
    }
}