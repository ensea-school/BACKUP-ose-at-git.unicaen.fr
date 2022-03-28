<?php

namespace Application\Form\Corps;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

/**
 * Description of CorpsSaisieFormFactory
 *
 * @author Joriot Florian
 */
class CorpsSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CorpsSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $form = new CorpsSaisieForm();
        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}