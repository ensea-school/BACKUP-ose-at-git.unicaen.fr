<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;



/**
 * Description of MissionFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionForm
    {
        $form = new MissionForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}