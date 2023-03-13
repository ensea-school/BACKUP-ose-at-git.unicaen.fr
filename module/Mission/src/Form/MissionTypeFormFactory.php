<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of TauxFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionTypeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTypeForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTypeForm
    {
        $form = new MissionTypeForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}