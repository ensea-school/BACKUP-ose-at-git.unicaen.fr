<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\DisciplineForm;


/**
 * Description of DisciplineFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DisciplineFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DisciplineForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DisciplineForm
    {
        $form = new DisciplineForm();

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

