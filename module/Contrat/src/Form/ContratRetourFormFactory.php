<?php

namespace Contrat\Form;

use Psr\Container\ContainerInterface;


class ContratRetourFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratRetourForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new ContratRetourForm();

        return $form;
    }
}