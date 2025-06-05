<?php

namespace Workflow\Form;

use Psr\Container\ContainerInterface;


class DependanceFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DependanceForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new DependanceForm();

        return $form;
    }
}