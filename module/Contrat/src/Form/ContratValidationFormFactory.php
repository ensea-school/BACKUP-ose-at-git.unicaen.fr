<?php

namespace Contrat\Form;

use Psr\Container\ContainerInterface;


class ContratValidationFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratValidationForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new ContratValidationForm();

        return $form;
    }
}