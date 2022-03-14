<?php

namespace Application\Form\TypeFormation;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

/**
 * Description of TypeFormationSaisieFormFactory
 *
 * @author Joriot Florian
 */
class TypeFormationSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeFormationSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new TypeFormationSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}