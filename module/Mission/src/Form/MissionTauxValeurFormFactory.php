<?php

namespace Mission\Form;

use Psr\Container\ContainerInterface;



/**
 * Description of MissionTauxFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionTauxValeurFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTauxValeurForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTauxValeurForm
    {
        $form = new MissionTauxValeurForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}