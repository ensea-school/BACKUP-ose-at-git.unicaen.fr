<?php

namespace Intervenant\Form;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

/**
 * Description of CorpsSaisieFormFactory
 *
 * @author Joriot Florian
 */
class GradeSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return GradeSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new GradeSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}