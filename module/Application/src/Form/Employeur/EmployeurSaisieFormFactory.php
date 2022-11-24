<?php

namespace Application\Form\Employeur;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;


class EmployeurSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return EmployeurSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new EmployeurSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}