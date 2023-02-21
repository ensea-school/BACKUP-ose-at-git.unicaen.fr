<?php

namespace OffreFormation\Form\TypeIntervention\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\TypeIntervention\TypeInterventionStructureSaisieForm;


/**
 * Description of TypeInterventionStructureSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionStructureSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionStructureSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionStructureSaisieForm
    {
        $form = new TypeInterventionStructureSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

