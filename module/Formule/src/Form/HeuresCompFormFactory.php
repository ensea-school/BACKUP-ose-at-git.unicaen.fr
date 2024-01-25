<?php

namespace Formule\Form;

use Psr\Container\ContainerInterface;


class HeuresCompFormFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): HeuresCompForm
    {
        $form = new HeuresCompForm();

        return $form;
    }

}