<?php

namespace OffreFormation\Form\TypeIntervention\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\TypeIntervention\TypeInterventionStatutDeleteForm;


/**
 * Description of TypeInterventionStatutDeleteFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionStatutDeleteFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionStatutDeleteForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionStatutDeleteForm
    {
        $form = new TypeInterventionStatutDeleteForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}