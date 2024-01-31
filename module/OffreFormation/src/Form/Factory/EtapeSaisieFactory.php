<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\EtapeSaisie;
use UnicaenImport\Service\SchemaService;


/**
 * Description of EtapeSaisieFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeSaisieFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeSaisie
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeSaisie
    {
        $form = new EtapeSaisie;

        $form->setServiceSchema($container->get(SchemaService::class));

        return $form;
    }
}

