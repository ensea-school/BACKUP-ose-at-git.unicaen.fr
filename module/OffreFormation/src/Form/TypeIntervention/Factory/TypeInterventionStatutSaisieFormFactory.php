<?php

namespace OffreFormation\Form\TypeIntervention\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\TypeIntervention\TypeInterventionStatutSaisieForm;


/**
 * Description of TypeInterventionStatutSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionStatutSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionStatutSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionStatutSaisieForm
    {
        $form = new TypeInterventionStatutSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

