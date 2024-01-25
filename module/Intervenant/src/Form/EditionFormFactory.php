<?php

namespace Intervenant\Form;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

/**
 * Description of EditionFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EditionFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EditionForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new EditionForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}