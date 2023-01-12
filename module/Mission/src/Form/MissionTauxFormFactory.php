<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;



/**
 * Description of MissionTauxFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionTauxFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTauxForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTauxForm
    {
        $form = new MissionTauxForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}