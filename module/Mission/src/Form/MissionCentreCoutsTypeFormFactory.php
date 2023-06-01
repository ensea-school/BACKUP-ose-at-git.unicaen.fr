<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of MissionCentreCoutsTypeFormFactory
 *
 * @author UnicaenCode
 */
class MissionCentreCoutsTypeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionCentreCoutsTypeForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionCentreCoutsTypeForm
    {
        $form = new MissionCentreCoutsTypeForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}

