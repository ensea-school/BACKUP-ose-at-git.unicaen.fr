<?php

namespace OffreFormation\Form\TypeIntervention\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\TypeIntervention\TypeInterventionSaisieForm;


/**
 * Description of TypeInterventionSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionSaisieForm
    {
        $form = new TypeInterventionSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

