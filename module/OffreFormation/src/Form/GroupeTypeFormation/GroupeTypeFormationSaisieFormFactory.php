<?php

namespace OffreFormation\Form\GroupeTypeFormation;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

/**
 * Description of GroupeTypeFormationSaisieFormFactory
 *
 * @author Joriot Florian
 */
class GroupeTypeFormationSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return GroupeTypeFormationSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new GroupeTypeFormationSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}