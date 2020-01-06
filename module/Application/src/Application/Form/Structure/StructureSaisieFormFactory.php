<?php

namespace Application\Form\Structure;

use Interop\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

/**
 * Description of StructureSaisieFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class StructureSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return StructureSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new StructureSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}